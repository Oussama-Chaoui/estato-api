<?php

namespace App\Models;

use App\Enums\PROPERTY_STATUS;
use App\Enums\PROPERTY_TYPE;
use App\Models\Classes\DataTableParams;
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
    'monthly_price',
    'daily_price',
    'sale_price',
    'daily_price_enabled',
    'monthly_price_enabled',
    'currency',
    'year_built',
    'lot_size',
    'type',
    'status',
    'has_vr',
  ];

  protected $with = [
    'location',
    'agents',
    'amenities',
    'images',
    'descriptions',
    'features',
    'rentals',
    'priceHistory',
  ];

  protected $casts = [
    'features' => 'array',
    'has_vr' => 'boolean',
  ];


  protected static function booted()
  {
    parent::booted();

    static::created(function ($property) {
      $agents = $property->agents;
      foreach ($agents as $agent) {
        if ($agent->user) {
          $agent->user->givePermission('properties.' . $property->id . '.read');
        }
      }
    });

    static::deleted(function ($property) {
      $agents = $property->agents;
      foreach ($agents as $agent) {
        if ($agent->user) {
          $agent->user->removeAllPermissions('properties', $property->id);
        }
      }
    });
  }

  public function scopeDataTable($query, DataTableParams $params)
  {
    \Log::info('DataTableParams:', [
      'checkPermission' => $params->checkPermission,
      'orderColumn' => $params->orderColumn,
      'orderDir' => $params->orderDir,
      'filterParam' => $params->filterParam,
    ]);

    if ($params->hasOrderParam()) {
      $query->dataTableSort($params->orderColumn, $params->orderDir);
    }

    if ($params->hasFilterParam()) {
      $this->filter($query, $params->filterParam);
    }

    return $query;
  }

  public function location()
  {
    return $this->belongsTo(Location::class);
  }

  public function agents()
  {
    return $this->belongsToMany(Agent::class, 'properties_agents')->using(PropertyAgent::class);
  }

  public function amenities()
  {
    return $this->belongsToMany(Amenity::class, 'properties_amenities')->withPivot('notes');
  }

  public function images()
  {
    return $this->hasMany(PropertyImage::class);
  }
  public function descriptions()
  {
    return $this->hasMany(PropertyDescription::class);
  }
  public function features()
  {
    return $this->hasMany(PropertyFeature::class);
  }

  public function rentals()
  {
    return $this->hasMany(PropertyRental::class);
  }

  public function priceHistory()
  {
    return $this->hasMany(PropertyPriceHistory::class);
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
      'monthly_price'  => 'required|numeric|min:0',
      'daily_price'    => 'required|numeric|min:0',
      'sale_price'     => 'required|numeric|min:0',
      'daily_price_enabled' => 'boolean',
      'monthly_price_enabled' => 'boolean',
      'currency'       => 'required|string',
      'year_built'     => 'required|integer',
      'lot_size'       => 'required|integer|min:0',
      'type' => [
        'required',
        'string',
        Rule::in(array_column(PROPERTY_TYPE::cases(), 'value'))
      ],
      'status' => [
        'required',
        'string',
        Rule::in(array_column(PROPERTY_STATUS::cases(), 'value'))
      ],
      'has_vr' => 'boolean',
    ];
  }
}
