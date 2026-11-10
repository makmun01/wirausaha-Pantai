<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migration_quarantine_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source_file', 150);
            $table->unsignedInteger('row_number');
            $table->json('raw_payload');
            $table->text('error_reason');
            $table->enum('resolution_status', ['QUARANTINED', 'RESOLVED', 'IGNORED'])->default('QUARANTINED');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migration_quarantine_logs');
    }
};
