<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('post_category', function (Blueprint $table) {
      $table->foreignId('post_id')->constrained()->onDelete('cascade');
      $table->foreignId('category_id')->constrained()->onDelete('cascade');
      $table->primary(['post_id', 'category_id']);
    });

    Schema::create('post_tag', function (Blueprint $table) {
      $table->foreignId('post_id')->constrained()->onDelete('cascade');
      $table->foreignId('tag_id')->constrained()->onDelete('cascade');
      $table->primary(['post_id', 'tag_id']);
    });
  }


  public function down()
  {
    Schema::dropIfExists('post_tag');
    Schema::dropIfExists('post_category');
  }
};
