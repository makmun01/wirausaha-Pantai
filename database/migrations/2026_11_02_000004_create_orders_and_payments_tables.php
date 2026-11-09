<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('umkm_merchant_id')->constrained('umkm_merchants');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['PENDING', 'PAID', 'PROCESSING', 'READY_FOR_PICKUP', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->enum('delivery_option', ['PICKUP', 'DELIVERY'])->default('PICKUP');
            $table->text('delivery_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['umkm_merchant_id', 'status', 'created_at']);
        });

        // 2. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('price_at_purchase', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamp('created_at')->useCurrent();
        });

        // 3. Payments Table (Simulasi Transaksi)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            $table->enum('payment_method', ['SIMULATION_QRIS', 'SIMULATION_VA', 'CASH_ON_LOCATION'])->default('SIMULATION_QRIS');
            $table->enum('payment_status', ['UNPAID', 'PAID', 'EXPIRED', 'REFUNDED'])->default('UNPAID');
            $table->string('simulation_ref_no', 100);
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_demo_gateway')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
