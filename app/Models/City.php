<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
  use HasFactory;

  protected $fillable = [
    'region_id',
    'names',
    'slug',
    'description',
    'latitude',
    'longitude',
  ];

  protected $casts = [
    'names' => 'array',
    'latitude' => 'decimal:8',
    'longitude' => 'decimal:8',
  ];

  /**
   * Get the region that owns the city.
   */
  public function region(): BelongsTo
  {
    return $this->belongsTo(Region::class);
  }

  /**
   * Get the locations for the city.
   */
  public function locations(): HasMany
  {
    return $this->hasMany(Location::class);
  }

  /**
   * Get the properties through locations.
   */
  public function properties()
  {
    return $this->hasManyThrough(Property::class, Location::class);
  }

  /**
   * Validation rules for cities.
   */
  public static function rules($id = null): array
  {
    return [
      'region_id'   => 'required|exists:regions,id',
      'names'       => 'required|array',
      'names.en'    => 'nullable|string|max:255',
      'names.fr'    => 'required|string|max:255',
      'names.es'    => 'nullable|string|max:255',
      'names.ar'    => 'required|string|max:255',
      'slug'        => 'required|string|max:255|unique:cities,slug' . ($id ? ",$id" : ''),
      'description' => 'nullable|string',
      'latitude'    => 'nullable|numeric|between:-90,90',
      'longitude'   => 'nullable|numeric|between:-180,180',
    ];
  }
}
