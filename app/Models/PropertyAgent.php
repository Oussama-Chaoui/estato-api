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
      $property = $pivot->property;
      $agent = $pivot->agent;

      if ($agent && $agent->user) {
        $agent->user->givePermission('properties.' . $property->id . '.read');
      }
    });

    static::deleted(function ($pivot) {
      $property = $pivot->property;
      $agent = $pivot->agent;

      if ($agent && $agent->user) {
        $agent->user->removeAllPermissions('properties', $property->id);
      }
    });
  }
}
