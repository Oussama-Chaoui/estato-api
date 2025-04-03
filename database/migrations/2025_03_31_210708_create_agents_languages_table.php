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
    Schema::create('agents_languages', function (Blueprint $table) {
      $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
      $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();

      $table->primary(['agent_id', 'language_id']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('agents_languages');
  }
};
