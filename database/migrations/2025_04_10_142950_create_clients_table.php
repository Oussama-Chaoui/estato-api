<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string('nic_number')->nullable();
      $table->string('passport')->nullable();

      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('image_id')->nullable()->constrained('uploads')->nullOnDelete();
      $table->timestamps();
    });

    DB::statement("ALTER TABLE clients ADD CONSTRAINT chk_client_identifiers CHECK (nic_number IS NOT NULL OR passport IS NOT NULL)");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('clients');
  }
};
