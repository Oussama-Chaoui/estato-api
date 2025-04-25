<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyFeature extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'bedrooms',
    'bathrooms',
    'area',
    'garages',
    'floors',
    'pool',
    'garden',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id' => 'required|exists:properties,id',
      'bedrooms'    => 'required|integer|min:0',
      'bathrooms'   => 'required|integer|min:0',
      'area'        => 'required|integer|min:0',
      'garages'     => 'required|integer|min:0',
      'floors'      => 'required|integer|min:1',
      'pool'        => 'boolean',
      'garden'      => 'boolean',
    ];
  }
}
