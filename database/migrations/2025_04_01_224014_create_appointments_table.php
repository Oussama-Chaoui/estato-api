<?php

use App\Enums\APPOINTMENT_STATUS;
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
    Schema::create('appointments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
      $table->timestamp('scheduled_at');
      $table->enum('status', array_column(APPOINTMENT_STATUS::cases(), 'value'))
        ->default(APPOINTMENT_STATUS::PENDING->value);
      $table->text('notes')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('appointments');
  }
};
