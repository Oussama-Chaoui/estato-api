<?php

namespace App\Models;

use App\Models\Classes\DataTableParams;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'city_id',
    'street_address',
    'latitude',
    'longitude',
  ];

  protected $casts = [
    'street_address' => 'array',
  ];

  public $with = ['city.region'];

  /**
   * Get the city that owns the location.
   */
  public function city()
  {
    return $this->belongsTo(City::class);
  }

  /**
   * Get the region through city.
   */
  public function region()
  {
    return $this->hasOneThrough(Region::class, City::class, 'id', 'id', 'city_id', 'region_id');
  }

  /**
   * Define relationship: A location can have many properties.
   */
  public function properties()
  {
    return $this->hasMany(Property::class);
  }

  public function scopeDataTable($query, DataTableParams $params)
  {
    if ($params->hasOrderParam()) {
      $query->dataTableSort($params->orderColumn, $params->orderDir);
    }

    if ($params->hasFilterParam()) {
      $this->filter($query, $params->filterParam);
    }

    return $query;
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
      'city_id'       => 'required|exists:cities,id',
      'street_address'    => 'required|array',
      'street_address.en' => 'nullable|string',
      'street_address.fr' => 'required|string',
      'street_address.es' => 'nullable|string',
      'street_address.ar' => 'required|string',
      'latitude'      => 'required|numeric|between:-90,90',
      'longitude'     => 'required|numeric|between:-180,180',
    ];
  }
}
