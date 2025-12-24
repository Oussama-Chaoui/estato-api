<?php

namespace App\Models;

use App\Enums\POST_STATUS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Post extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'agent_id',
    'title',
    'slug',
    'excerpt',
    'content',
    'status',
    'published_at',
    'image_id',
    'meta_title',
    'meta_description',
  ];

  protected $with = [
    'agent.user',
    'categories',
    'tags',
    'images.upload',
    'image'
  ];

  protected $casts = [
    'published_at' => 'datetime',
    'status'       => POST_STATUS::class,
    'title' => 'array',
    'excerpt' => 'array',
    'content' => 'array',
    'meta_title' => 'array',
    'meta_description' => 'array',
  ];

  protected static function booted()
  {
    parent::booted();

    static::created(function (Post $post) {
      if ($post->agent && $post->agent->user) {
        $user = $post->agent->user;
        $user->givePermission('posts' . $post->id . 'update');
        $user->givePermission('posts' . $post->id, 'delete');
      }
    });

    static::deleted(function (Post $post) {
      if ($post->agent && $post->agent->user) {
        $post->agent->user->removeAllPermissions('posts', $post->id);
      }
    });
  }

  public function image()
  {
    return $this->belongsTo(Upload::class, 'image_id');
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class, 'agent_id');
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class, 'post_category');
  }

  public function tags()
  {
    return $this->belongsToMany(Tag::class, 'post_tag');
  }

  public function images()
  {
    return $this->hasMany(PostImage::class);
  }

  public function scopeDataTable($query, \App\Models\Classes\DataTableParams $params)
  {
    if ($params->hasOrderParam()) {
      $query->dataTableSort($params->orderColumn, $params->orderDir);
    }

    if ($params->hasFilterParam()) {
      $this->filter($query, $params->filterParam);
    }

    return $query;
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    return [
      'agent_id'         => 'required|exists:agents,id',
      'title'            => 'required|array',
      'title.en'         => 'nullable|string|max:255',
      'title.fr'         => 'required|string|max:255',
      'title.es'         => 'nullable|string|max:255',
      'title.ar'         => 'required|string|max:255',
      'slug'             => [
        'required',
        'string',
        'max:255',
        Rule::unique('posts', 'slug')->ignore($id),
      ],
      'excerpt'          => 'nullable|array',
      'excerpt.en'       => 'nullable|string',
      'excerpt.fr'       => 'nullable|string',
      'excerpt.es'       => 'nullable|string',
      'excerpt.ar'       => 'nullable|string',
      'content'          => 'required|array',
      'content.en'       => 'nullable|string',
      'content.fr'       => 'required|string',
      'content.es'       => 'nullable|string',
      'content.ar'       => 'required|string',
      'status'           => [
        'required',
        'string',
        Rule::in(array_column(POST_STATUS::cases(), 'value')),
      ],
      'published_at'     => 'nullable|date',
      'image_id'         => 'required|exists:uploads,id',
      'meta_title'       => 'nullable|array',
      'meta_title.en'    => 'nullable|string|max:255',
      'meta_title.fr'    => 'nullable|string|max:255',
      'meta_title.es'    => 'nullable|string|max:255',
      'meta_title.ar'    => 'nullable|string|max:255',
      'meta_description' => 'nullable|array',
      'meta_description.en' => 'nullable|string|max:500',
      'meta_description.fr' => 'nullable|string|max:500',
      'meta_description.es' => 'nullable|string|max:500',
      'meta_description.ar' => 'nullable|string|max:500',
    ];
  }
}
