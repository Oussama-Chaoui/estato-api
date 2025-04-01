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
    Schema::create('properties_agents', function (Blueprint $table) {
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();

      $table->primary(['property_id', 'agent_id']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('properties_agents');
  }
};
