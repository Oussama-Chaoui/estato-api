<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
  use HasFactory;

  protected $fillable = [
    'names',
    'slug',
    'description',
  ];

  protected $casts = [
    'names' => 'array',
  ];

  /**
   * Get the cities for the region.
   */
  public function cities(): HasMany
  {
    return $this->hasMany(City::class);
  }

  /**
   * Get the locations through cities.
   */
  public function locations(): HasMany
  {
    return $this->hasManyThrough(Location::class, City::class);
  }

  /**
   * Get the properties through cities and locations.
   */
  public function properties()
  {
    return $this->hasManyThrough(Property::class, Location::class, 'city_id', 'location_id', 'id', 'id')
      ->join('cities', 'cities.id', '=', 'locations.city_id')
      ->where('cities.region_id', $this->id);
  }

  /**
   * Validation rules for regions.
   */
  public static function rules($id = null): array
  {
    return [
      'names'       => 'required|array',
      'names.en'    => 'nullable|string|max:255',
      'names.fr'    => 'required|string|max:255',
      'names.es'    => 'nullable|string|max:255',
      'names.ar'    => 'required|string|max:255',
      'slug'        => 'required|string|max:255|unique:regions,slug' . ($id ? ",$id" : ''),
      'description' => 'nullable|string',
    ];
  }
}
