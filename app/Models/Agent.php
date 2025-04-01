<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'license_number',
    'experience',
    'bio',
    'photo_id',
    'agancy_name',
    'agency_address',
    'user_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function photo()
  {
    return $this->belongsTo(Upload::class, 'photo_id');
  }

  public function languages()
  {
    return $this->belongsToMany(Language::class, 'agents_languages');
  }

  public function properties()
  {
    return $this->belongsToMany(Property::class, 'properties_agents');
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    $rules = [
      'licence_number' => 'required|string|unique:agents,licence_number' . ($id ? ',' . $id : ''),
      'experience'     => 'required|integer|min:0',
      'bio'            => 'nullable|string',
      'photo_id'       => 'nullable|exists:uploads,id',
      'agancy_name'    => 'required|string',
      'agency_address' => 'required|string',
      'user_id'        => 'required|exists:users,id',
    ];

    return $rules;
  }
}
