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
    Schema::create('agents', function (Blueprint $table) {
      $table->id();
      $table->string('licence_number')->unique();
      $table->integer('experience')->default(0);
      $table->string('bio')->nullable();
      $table->foreignId('photo_id')->nullable()->constrained('uploads')->cascadeOnDelete();
      $table->string('agancy_name');
      $table->string('agency_address');
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('agents');
  }
};
