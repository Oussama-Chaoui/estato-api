<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyAgent extends Pivot
{
  protected $table = 'properties_agents';

  protected $fillable = [
    'property_id',
    'agent_id',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class);
  }

  public static function booted()
  {

    parent::booted();

    static::created(function ($pivot) {
      $agent = $pivot->agent;
      $propertyId = $pivot->property_id;

      if ($agent && $agent->user && $propertyId) {
        $agent->user->givePermission('properties.' . $propertyId . '.read');
      }
    });

    static::deleted(function ($pivot) {
      $agent = $pivot->agent;
      $propertyId = $pivot->property_id;

      if ($agent && $agent->user && $propertyId) {
        $agent->user->removeAllPermissions('properties', $propertyId);
      }
    });
  }
}
