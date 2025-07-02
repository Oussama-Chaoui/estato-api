<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Tag extends BaseModel
{
  use HasFactory;

  protected $fillable = ['name', 'slug'];

  public function posts()
  {
    return $this->belongsToMany(Post::class);
  }

  public function rules($id = null): array
  {
    $id = $id ?? request()->route('id');

    return [
      'name' => 'required|string|max:50',
      'slug' => [
        'required',
        'string',
        'max:50',
        Rule::unique('tags', 'slug')->ignore($id),
      ],
    ];
  }
}
