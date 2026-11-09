<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Destinations Table (Pantai Jumiang)
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 191)->unique();
            $table->longText('description');
            $table->string('village', 100)->default('Tanjung');
            $table->string('district', 100)->default('Pademawu');
            $table->string('regency', 100)->default('Pamekasan');
            $table->string('operating_hours', 100);
            $table->decimal('ticket_price_demo', 12, 2)->default(10000.00);
            $table->boolean('is_demo_data')->default(true);
            $table->timestamps();
        });

        // 2. Attractions Table (Spot Tebing, Spot Pasir, dll)
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('safety_level', ['SAFE', 'WARNING', 'RESTRICTED'])->default('SAFE');
            $table->string('cover_image', 255)->nullable();
            $table->timestamps();
        });

        // 3. Facilities Table
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('category', 50); // IBADAH, SANITASI, ISTIRAHAT, PARKIR
            $table->enum('condition', ['BAIK', 'PERBAIKAN', 'RUSAK'])->default('BAIK');
            $table->timestamps();
        });

        // 4. Galleries Table
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('title', 150);
            $table->string('image_path', 255);
            $table->string('caption', 255)->nullable();
            $table->timestamps();
        });

        // 5. Map Markers Table (WGS84 Leaflet GIS)
        Schema::create('map_markers', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['WISATA', 'UMKM', 'FASILITAS', 'HOMESTAY', 'PARKIR'])->default('WISATA');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('icon_type', 50)->default('default');
            $table->string('related_entity_type', 100)->nullable();
            $table->unsignedBigInteger('related_entity_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_markers');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('attractions');
        Schema::dropIfExists('destinations');
    }
};
