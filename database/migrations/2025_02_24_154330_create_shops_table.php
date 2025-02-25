<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('postcode', 7);
            $table->decimal('lat', 11, 8);
            $table->decimal('long', 11, 8);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->tinyInteger('store_type');
            $table->integer('max_delivery_km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
