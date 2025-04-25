<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  use HasFactory;

  protected $fillable = [
    'nic_number',
    'passport',
    'user_id',
    'image_id',
  ];

  protected $with = [
    'user',
    'image',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function image()
  {
    return $this->belongsTo(Upload::class, 'image_id');
  }

  public function rules()
  {
    return [
      'nic_number' => 'nullable|required_without:passport|string',
      'passport'   => 'nullable|required_without:nic_number|string',
      'user_id'    => 'required|exists:users,id',
      'image_id'   => 'nullable|exists:uploads,id',
    ];
  }
}
