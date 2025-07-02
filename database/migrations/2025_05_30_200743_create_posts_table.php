<?php

use App\Enums\POST_STATUS;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
      $table->string('title');
      $table->string('slug')->unique();
      $table->text('excerpt')->nullable();
      $table->longText('content');
      $table->enum('status', array_column(POST_STATUS::cases(), 'value'))->default('draft');
      $table->timestamp('published_at')->nullable();
      $table->foreignId('image_id')->constrained('uploads')->cascadeOnDelete();
      // SEO meta
      $table->string('meta_title')->nullable();
      $table->string('meta_description')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
