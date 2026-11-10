<?php

declare(strict_types=1);

namespace {
    $vendorAutoload = __DIR__ . '/../vendor/autoload.php';
    $hasVendor = is_file($vendorAutoload);
    if ($hasVendor) {
        require_once $vendorAutoload;
    }

    if (!$hasVendor) {
        spl_autoload_register(static function (string $class): void {
            $prefixes = [
                'App\\' => __DIR__ . '/../app/',
                'Tests\\' => __DIR__ . '/',
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                if (str_starts_with($class, $prefix)) {
                    $relative = substr($class, strlen($prefix));
                    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
                    if (is_file($file)) {
                        require_once $file;
                        return;
                    }
                }
            }
        });
    }

    if (!function_exists('now')) {
        function now(): string
        {
            return date('Y-m-d H:i:s');
        }
    }
}

namespace Illuminate\Support {
    if (!class_exists(Str::class)) {
        class Str
        {
            public static function random(int $length = 16): string
            {
                return substr(bin2hex(random_bytes((int) ceil($length / 2))), 0, $length);
            }

            public static function slug(string $title, string $separator = '-'): string
            {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', $separator, $title) ?? '', $separator));
                return $slug !== '' ? $slug : 'item';
            }
        }
    }
}

namespace Illuminate\Support\Facades {
    if (!class_exists(DB::class)) {
        class DB
        {
            public static function transaction(callable $callback)
            {
                return $callback();
            }
        }
    }
}

namespace Illuminate\Database\Eloquent\Relations {
    if (!class_exists(Relation::class)) {
        class Relation
        {
            protected $parent;
            protected string $related;
            protected string $foreignKey;

            public function __construct($parent, string $related, string $foreignKey)
            {
                $this->parent = $parent;
                $this->related = $related;
                $this->foreignKey = $foreignKey;
            }
        }

        class BelongsTo extends Relation {}
        class HasMany extends Relation {}
        class HasOne extends Relation {}
    }
}

namespace Illuminate\Database\Eloquent {
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;

    if (!class_exists(Model::class)) {
        class ModelNotFoundException extends \RuntimeException {}

        class Collection implements \Countable, \IteratorAggregate
        {
            /** @var array<int, mixed> */
            private array $items;

            public function __construct(array $items = [])
            {
                $this->items = array_values($items);
            }

            public function count(): int
            {
                return count($this->items);
            }

            public function getIterator(): \Traversable
            {
                return new \ArrayIterator($this->items);
            }

            public function where(string $key, $value): self
            {
                return new self(array_filter($this->items, static fn ($item) => ($item->$key ?? null) === $value));
            }

            public function sum(string $key): float
            {
                $total = 0.0;
                foreach ($this->items as $item) {
                    $total += (float) ($item->$key ?? 0);
                }
                return $total;
            }

            public function all(): array
            {
                return $this->items;
            }
        }

        class QueryBuilder
        {
            private string $modelClass;
            private array $wheres = [];
            private array $withRelations = [];

            public function __construct(string $modelClass)
            {
                $this->modelClass = $modelClass;
            }

            public function lockForUpdate(): self
            {
                return $this;
            }

            public function with($relations): self
            {
                $this->withRelations = is_array($relations) ? $relations : func_get_args();
                return $this;
            }

            public function where(string $column, $operatorOrValue, $value = null): self
            {
                if (func_num_args() === 2) {
                    $this->wheres[] = [$column, '=', $operatorOrValue];
                } else {
                    $this->wheres[] = [$column, $operatorOrValue, $value];
                }
                return $this;
            }

            public function whereColumn(string $col1, string $op, string $col2): self
            {
                $this->wheres[] = ['__COLUMN__', $col1, $op, $col2];
                return $this;
            }

            public function whereBetween(string $col, array $range): self
            {
                $this->wheres[] = ['__BETWEEN__', $col, $range[0], $range[1]];
                return $this;
            }

            public function find($id): ?Model
            {
                return Model::findFromStore($this->modelClass, $id);
            }

            public function findOrFail($id): Model
            {
                $record = $this->find($id);
                if ($record === null) {
                    throw new ModelNotFoundException("Model {$this->modelClass} #{$id} tidak ditemukan.");
                }
                return $record;
            }

            public function get(): Collection
            {
                $rows = Model::getAllFromStore($this->modelClass);
                $filtered = array_filter($rows, function (Model $row): bool {
                    foreach ($this->wheres as $cond) {
                        if ($cond[0] === '__COLUMN__') {
                            [, $c1, $op, $c2] = $cond;
                            $v1 = $row->$c1 ?? null;
                            $v2 = $row->$c2 ?? null;
                            if ($op === '<=' && !($v1 <= $v2)) {
                                return false;
                            }
                            continue;
                        }
                        if ($cond[0] === '__BETWEEN__') {
                            [, $col, $min, $max] = $cond;
                            $val = $row->$col ?? null;
                            if ($val < $min || $val > $max) {
                                return false;
                            }
                            continue;
                        }
                        [$col, $op, $val] = $cond;
                        $actual = $row->$col ?? null;
                        if ($op === '=' && $actual != $val) {
                            return false;
                        }
                    }
                    return true;
                });

                return new Collection($filtered);
            }

            public function first(): ?Model
            {
                $all = $this->get()->all();
                return $all[0] ?? null;
            }

            public function firstOrFail(): Model
            {
                $record = $this->first();
                if ($record === null) {
                    throw new ModelNotFoundException("Model {$this->modelClass} tidak ditemukan.");
                }
                return $record;
            }

            public function firstOrCreate(array $attributes, array $values = []): Model
            {
                foreach ($attributes as $k => $v) {
                    $this->where($k, $v);
                }
                $existing = $this->first();
                if ($existing !== null) {
                    return $existing;
                }
                return ($this->modelClass)::create(array_merge($attributes, $values));
            }
        }

        class Model
        {
            /** @var array<string, array<int|string, self>> */
            protected static array $store = [];
            /** @var array<string, int> */
            protected static array $autoIds = [];

            protected $fillable = [];
            protected $casts = [];
            protected array $attributes = [];

            public function __construct(array $attributes = [])
            {
                $this->attributes = $attributes;
            }

            public static function resetStore(): void
            {
                self::$store = [];
                self::$autoIds = [];
            }

            public static function getAllFromStore(string $class): array
            {
                return self::$store[$class] ?? [];
            }

            public static function findFromStore(string $class, $id): ?self
            {
                return self::$store[$class][$id] ?? null;
            }

            public static function query(): QueryBuilder
            {
                return new QueryBuilder(static::class);
            }

            public static function lockForUpdate(): QueryBuilder
            {
                return static::query()->lockForUpdate();
            }

            public static function where(string $column, $operatorOrValue, $value = null): QueryBuilder
            {
                if (func_num_args() === 2) {
                    return static::query()->where($column, $operatorOrValue);
                }
                return static::query()->where($column, $operatorOrValue, $value);
            }

            public static function with($relations): QueryBuilder
            {
                return static::query()->with($relations);
            }

            public static function find($id): ?self
            {
                return static::query()->find($id);
            }

            public static function findOrFail($id): self
            {
                return static::query()->findOrFail($id);
            }

            public static function firstOrFail(): self
            {
                return static::query()->firstOrFail();
            }

            public static function firstOrCreate(array $attributes, array $values = []): self
            {
                return static::query()->firstOrCreate($attributes, $values);
            }

            public static function create(array $attributes): self
            {
                $instance = new static($attributes);
                $instance->save();
                return $instance;
            }

            public function save(): bool
            {
                $class = static::class;
                if (!isset($this->attributes['id'])) {
                    self::$autoIds[$class] = (self::$autoIds[$class] ?? 0) + 1;
                    $this->attributes['id'] = self::$autoIds[$class];
                }
                self::$store[$class][$this->attributes['id']] = $this;
                return true;
            }

            public function load($relations): self
            {
                return $this;
            }

            public function __get(string $key)
            {
                if (array_key_exists($key, $this->attributes)) {
                    return $this->attributes[$key];
                }

                if ($this instanceof \App\Models\Order && $key === 'items') {
                    return \App\Models\OrderItem::where('order_id', $this->id)->get();
                }
                if ($this instanceof \App\Models\Order && $key === 'payment') {
                    return \App\Models\Payment::where('order_id', $this->id)->first();
                }

                return null;
            }

            public function __set(string $key, $value): void
            {
                $this->attributes[$key] = $value;
            }

            public function belongsTo(string $related, ?string $foreignKey = null): BelongsTo
            {
                return new BelongsTo($this, $related, $foreignKey ?? 'id');
            }

            public function hasMany(string $related, ?string $foreignKey = null): HasMany
            {
                return new HasMany($this, $related, $foreignKey ?? 'parent_id');
            }

            public function hasOne(string $related, ?string $foreignKey = null): HasOne
            {
                return new HasOne($this, $related, $foreignKey ?? 'parent_id');
            }
        }
    }
}

namespace PHPUnit\Framework {
    if (!class_exists(TestCase::class)) {
        class TestCase
        {
            protected function setUp(): void
            {
            }

            public function runSetUp(): void
            {
                $this->setUp();
            }
        }
    }
}

namespace Tests {
    class Assert
    {
        public static function true(bool $condition, string $message = ''): void
        {
            if (!$condition) {
                throw new \AssertionError($message !== '' ? $message : 'Expected condition to be true.');
            }
        }

        public static function false(bool $condition, string $message = ''): void
        {
            if ($condition) {
                throw new \AssertionError($message !== '' ? $message : 'Expected condition to be false.');
            }
        }

        public static function same($expected, $actual, string $message = ''): void
        {
            if ($expected !== $actual) {
                $expStr = var_export($expected, true);
                $actStr = var_export($actual, true);
                throw new \AssertionError(($message !== '' ? $message . ' ' : '') . "Expected {$expStr}, got {$actStr}");
            }
        }

        public static function equals($expected, $actual, string $message = ''): void
        {
            if ($expected != $actual) {
                $expStr = var_export($expected, true);
                $actStr = var_export($actual, true);
                throw new \AssertionError(($message !== '' ? $message . ' ' : '') . "Expected {$expStr}, got {$actStr}");
            }
        }

        public static function contains(string $needle, string $haystack, string $message = ''): void
        {
            if (!str_contains($haystack, $needle)) {
                throw new \AssertionError(($message !== '' ? $message . ' ' : '') . "Expected string to contain '{$needle}'.");
            }
        }

        public static function notContains(string $needle, string $haystack, string $message = ''): void
        {
            if (str_contains($haystack, $needle)) {
                throw new \AssertionError(($message !== '' ? $message . ' ' : '') . "Expected string NOT to contain '{$needle}'.");
            }
        }

        public static function throws(string $exceptionClass, callable $fn, string $message = ''): void
        {
            try {
                $fn();
            } catch (\Throwable $e) {
                if ($e instanceof $exceptionClass) {
                    return;
                }
                throw new \AssertionError(
                    ($message !== '' ? $message . ' ' : '') .
                    "Expected {$exceptionClass}, got " . get_class($e) . ": " . $e->getMessage()
                );
            }
            throw new \AssertionError(($message !== '' ? $message . ' ' : '') . "Expected exception {$exceptionClass} was not thrown.");
        }
    }
}
