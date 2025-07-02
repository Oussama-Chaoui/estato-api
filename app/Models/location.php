<?php

namespace App\Models;

use App\Models\Classes\DataTableParams;
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
      'region'    => 'required|string|max:255',
      'city'      => 'required|string|max:255',
      'latitude'  => 'required|numeric',
      'longitude' => 'required|numeric',
    ];
  }
}
