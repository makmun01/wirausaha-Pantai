<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Reservations Table (Tiket & Paket Wisata)
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('destination_id')->constrained('destinations');
            $table->date('visit_date');
            $table->integer('visitor_count')->default(1);
            $table->string('package_name', 100)->default('Tiket Masuk Standar');
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['PENDING', 'CONFIRMED', 'ATTENDED', 'CANCELLED'])->default('PENDING');
            $table->timestamp('check_in_time')->nullable();
            $table->timestamps();

            $table->index(['visit_date', 'status']);
        });

        // 2. Partnerships Table (MoU & Kerja Sama)
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name', 150);
            $table->string('partnership_type', 100); // AKADEMISI, PEMERINTAH, KOMUNITAS, SPONSOR
            $table->string('contact_person', 100);
            $table->string('phone_number', 30)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['ACTIVE', 'PENDING', 'EXPIRED', 'TERMINATED'])->default('ACTIVE');
            $table->string('document_url', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnerships');
        Schema::dropIfExists('reservations');
    }
};
