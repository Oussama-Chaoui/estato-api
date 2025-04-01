<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'region',
    'city',
    'latitude',
    'longitude',
  ];

  /**
   * Define relationship: A location can have many properties.
   */
  public function properties()
  {
    return $this->hasMany(Property::class);
  }

  /**
   * Get validation rules for the location.
   *
   * @param mixed $id Optional ID for update scenarios.
   * @return array
   */
  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    return [
      'region'    => 'required|string|max:255',
      'city'      => 'required|string|max:255',
      'latitude'  => 'required|numeric',
      'longitude' => 'required|numeric',
    ];
  }
}
