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
    Schema::create('property_rentals', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('property_id');
      $table->unsignedBigInteger('client_id');
      $table->unsignedBigInteger('agent_id');
      $table->dateTime('start_date');
      $table->dateTime('end_date');
      $table->decimal('price', 10, 2);
      $table->timestamps();

      $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
      $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
      $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('property_rentals');
  }
};
