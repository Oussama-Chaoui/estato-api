<?php

use App\Enums\AGENT_APPLICATION_STATUS;
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
    Schema::create('agents_appliances', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->nullable();
      $table->string('phone');
      $table->enum('status', array_column(AGENT_APPLICATION_STATUS::cases(), 'value'))
        ->default(AGENT_APPLICATION_STATUS::PENDING->value);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('agents_appliances');
  }
};
