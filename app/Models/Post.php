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

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    return [
      'agent_id'         => 'required|exists:agents,id',
      'title'            => 'required|string|max:255',
      'slug'             => [
        'required',
        'string',
        'max:255',
        Rule::unique('posts', 'slug')->ignore($id),
      ],
      'excerpt'          => 'nullable|string',
      'content'          => 'required|string',
      'status'           => [
        'required',
        'string',
        Rule::in(array_column(POST_STATUS::cases(), 'value')),
      ],
      'published_at'     => 'nullable|date',
      'image_id'         => 'required|exists:uploads,id',
      'meta_title'       => 'nullable|string|max:255',
      'meta_description' => 'nullable|string|max:500',
    ];
  }
}
