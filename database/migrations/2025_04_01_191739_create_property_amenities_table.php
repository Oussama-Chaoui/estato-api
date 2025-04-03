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
    Schema::create('properties_amenities', function (Blueprint $table) {
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
      $table->string('notes')->nullable();
      $table->primary(['property_id', 'amenity_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('properties_amenities');
  }
};
