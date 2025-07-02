<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Category extends BaseModel
{
  use HasFactory;

  protected $fillable = ['name', 'slug', 'description'];

  public function posts()
  {
    return $this->belongsToMany(Post::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    return [
      'name'        => 'required|string|max:100',
      'slug'        => [
        'required',
        'string',
        'max:100',
        Rule::unique('categories', 'slug')->ignore($id),
      ],
      'description' => 'nullable|string',
    ];
  }
}
