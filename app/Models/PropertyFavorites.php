<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends BaseModel
{
  protected $table = 'property_favorites';

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'property_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'user_id' => 'required|exists:users,id',
      'property_id' => 'required|exists:properties,id',
    ];
  }
}
