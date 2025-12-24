<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Category extends BaseModel
{
  use HasFactory;

  protected $fillable = ['name', 'slug', 'description'];

  protected $casts = [
    'name' => 'array',
    'description' => 'array',
  ];

  public function posts()
  {
    return $this->belongsToMany(Post::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    return [
      'name'        => 'required|array',
      'name.en'     => 'nullable|string|max:100',
      'name.fr'     => 'required|string|max:100',
      'name.es'     => 'nullable|string|max:100',
      'name.ar'     => 'required|string|max:100',
      'slug'        => [
        'required',
        'string',
        'max:100',
        Rule::unique('categories', 'slug')->ignore($id),
      ],
      'description' => 'nullable|array',
      'description.en' => 'nullable|string',
      'description.fr' => 'nullable|string',
      'description.es' => 'nullable|string',
      'description.ar' => 'nullable|string',
    ];
  }
}
