<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Tag extends BaseModel
{
  use HasFactory;

  protected $fillable = ['name', 'slug'];

  protected $casts = [
    'name' => 'array',
  ];

  public function posts()
  {
    return $this->belongsToMany(Post::class);
  }

  public function rules($id = null): array
  {
    $id = $id ?? request()->route('id');

    return [
      'name' => 'required|array',
      'name.en' => 'nullable|string|max:50',
      'name.fr' => 'required|string|max:50',
      'name.es' => 'nullable|string|max:50',
      'name.ar' => 'required|string|max:50',
      'slug' => [
        'required',
        'string',
        'max:50',
        Rule::unique('tags', 'slug')->ignore($id),
      ],
    ];
  }
}
