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
    Schema::create('property_features', function (Blueprint $table) {
      $table->id();
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->integer('bedrooms');
      $table->integer('bathrooms');
      $table->integer('area');
      $table->integer('garages');
      $table->integer('floors')->default(1);
      $table->boolean('pool')->default(false);
      $table->boolean('garden')->default(false);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('property_features');
  }
};
