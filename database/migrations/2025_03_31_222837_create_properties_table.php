<?php

use App\Enums\FURNISHING_STATUS;
use App\Enums\PROPERTY_STATUS;
use App\Enums\PROPERTY_TYPE;
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
    Schema::create('properties', function (Blueprint $table) {
      $table->id();
      $table->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnDelete();
      $table->json('title');
      $table->json('description');
      $table->decimal('monthly_price', 10, 2)->nullable();
      $table->decimal('daily_price', 10, 2)->nullable();
      $table->decimal('sale_price', 10, 2)->nullable();
      $table->boolean('daily_price_enabled')->default(true);
      $table->boolean('monthly_price_enabled')->default(true);
      $table->string('currency')->default('MAD');
      $table->integer('year_built');
      $table->enum('type', array_column(PROPERTY_TYPE::cases(), 'value'));
      $table->enum('status', array_column(PROPERTY_STATUS::cases(), 'value'))
        ->default(PROPERTY_STATUS::FOR_SALE->value);
      $table->boolean('has_vr')->default(false);
      $table->boolean('featured')->default(false);
      $table->enum('furnishing_status', array_column(FURNISHING_STATUS::cases(), 'value'))
        ->default(FURNISHING_STATUS::FURNISHED->value);
      $table->timestamp('sold_at')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('properties');
  }
};
