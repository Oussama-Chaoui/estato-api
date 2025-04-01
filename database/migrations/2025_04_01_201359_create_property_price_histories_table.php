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
    Schema::create('property_price_histories', function (Blueprint $table) {
      $table->id();
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->decimal('price', 15, 2);
      $table->string('currency')->default('MAD');
      $table->timestamp('recorded_at');
      $table->string('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('property_price_histories');
  }
};
