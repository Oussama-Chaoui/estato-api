<?php

namespace App\Models;

use App\Enums\PROPERTY_STATUS;
use App\Enums\PROPERTY_TYPE;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Property extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'location_id',
    'title',
    'street_address',
    'description',
    'price',
    'currency',
    'year_built',
    'lot_size',
    'property_type',
    'status',
    'has_vr',
  ];

  /**
   * Get the location associated with the property.
   */
  public function location()
  {
    return $this->belongsTo(Location::class);
  }

  public function agents()
  {
    return $this->belongsToMany(Agent::class, 'properties_agents');
  }

  public function amenities()
  {
    return $this->belongsToMany(Amenity::class, 'properties_amenities')->withPivot('notes');
  }

  /**
   * Get the validation rules for a property.
   *
   * @param mixed $id Optional ID for update scenarios.
   * @return array
   */
  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'location_id'    => 'required|exists:locations,id',
      'title'          => 'required|string|max:255',
      'street_address' => 'required|string',
      'description'    => 'required|string',
      'price'          => 'required|numeric|min:0',
      'currency'       => 'required|string',
      'year_built'     => 'required|integer',
      'lot_size'       => 'required|integer|min:0',
      'property_type'  => [
        'required',
        'string',
        Rule::in(array_column(PROPERTY_TYPE::cases(), 'value'))
      ],
      'status'         => [
        'required',
        'string',
        Rule::in(array_column(PROPERTY_STATUS::cases(), 'value'))
      ],
      'has_vr'         => 'boolean',
    ];
  }
}
