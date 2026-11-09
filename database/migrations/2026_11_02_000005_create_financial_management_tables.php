<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Financial Accounts (Rekening Kas Mandiri UMKM: Usaha vs Pribadi)
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_merchant_id')->constrained('umkm_merchants')->onDelete('cascade');
            $table->string('account_name', 100);
            $table->enum('account_type', ['BUSINESS', 'PERSONAL'])->default('BUSINESS');
            $table->decimal('initial_balance', 14, 2)->default(0.00);
            $table->decimal('current_balance', 14, 2)->default(0.00);
            $table->timestamps();

            $table->index(['umkm_merchant_id', 'account_type']);
        });

        // 2. Financial Categories
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['INCOME', 'EXPENSE']);
            $table->boolean('is_system_default')->default(false);
            $table->timestamps();
        });

        // 3. Financial Transactions (Jurnal Arus Kas UMKM)
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_merchant_id')->constrained('umkm_merchants')->onDelete('cascade');
            $table->foreignId('financial_account_id')->constrained('financial_accounts');
            $table->foreignId('category_id')->constrained('financial_categories');
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->enum('transaction_type', ['INCOME', 'EXPENSE']);
            $table->decimal('amount', 14, 2);
            $table->date('transaction_date');
            $table->text('description');
            $table->string('receipt_image', 255)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['umkm_merchant_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('financial_categories');
        Schema::dropIfExists('financial_accounts');
    }
};
