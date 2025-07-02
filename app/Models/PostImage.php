<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends BaseModel
{
  use HasFactory;

  protected $fillable = ['post_id', 'image_id', 'alt_text', 'order'];

  public function post()
  {
    return $this->belongsTo(Post::class);
  }

  public function upload()
  {
    return $this->belongsTo(Upload::class, 'image_id');
  }

  public function rules($id = null)
  {
    return [
      'post_id'  => 'required|exists:posts,id',
      'image_id' => 'required|exists:uploads,id',
      'alt_text' => 'nullable|string|max:255',
      'order'    => 'nullable|integer|min:0',
    ];
  }
}
