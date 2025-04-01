<?php

use App\Enums\INQUIRY_STATUS;
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
    Schema::create('inquiries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
      $table->string('name');
      $table->string('email');
      $table->string('phone');
      $table->text('message');
      $table->enum('status', array_column(INQUIRY_STATUS::cases(), 'value'))
        ->default(INQUIRY_STATUS::NEW->value);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('inquiries');
  }
};
