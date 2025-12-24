<?php

namespace App\Models;

use App\Enums\FURNISHING_STATUS;
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
    'description',
    'monthly_price',
    'daily_price',
    'sale_price',
    'daily_price_enabled',
    'monthly_price_enabled',
    'currency',
    'year_built',
    'type',
    'status',
    'has_vr',
    'featured',
    'furnishing_status',
    'sold_at',
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
    'featured' => 'boolean',
    'title' => 'array',
    'description' => 'array',
    'sold_at' => 'datetime',
  ];


  protected static function booted()
  {
    parent::booted();

    // Global scope to exclude sold properties from all queries
    static::addGlobalScope('not_sold', function ($query) {
      $query->where('status', '!=', PROPERTY_STATUS::SOLD->value);
    });

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

  /**
   * Scope to include sold properties (for admin purposes)
   */
  public function scopeWithSold($query)
  {
    return $query->withoutGlobalScope('not_sold');
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
      'location_id'    => $id ? 'required|exists:locations,id' : 'nullable|exists:locations,id',
      'title'          => 'required|array',
      'title.en'       => 'nullable|string|max:255',
      'title.fr'       => 'required|string|max:255',
      'title.es'       => 'nullable|string|max:255',
      'title.ar'       => 'required|string|max:255',
      'description'    => 'required|array',
      'description.en' => 'nullable|string',
      'description.fr' => 'required|string',
      'description.es' => 'nullable|string',
      'description.ar' => 'required|string',
      'monthly_price'  => 'required|numeric|min:0',
      'daily_price'    => 'required|numeric|min:0',
      'sale_price'     => 'required|numeric|min:0',
      'daily_price_enabled' => 'boolean',
      'monthly_price_enabled' => 'boolean',
      'currency'       => 'required|string',
      'year_built'     => 'required|integer',
      'type' => [
        'required',
        'string',
        Rule::in(array_column(PROPERTY_TYPE::cases(), 'value'))
      ],
      'has_vr' => 'boolean',
      'featured' => 'boolean',
      'furnishing_status' => [
        'required',
        'string',
        Rule::in(array_column(FURNISHING_STATUS::cases(), 'value'))
      ],
    ];
  }
}
