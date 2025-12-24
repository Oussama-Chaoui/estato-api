<?php

namespace App\Models;

use App\Enums\RENTAL_TYPE;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PropertyRental extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'client_id',
    'agent_id',
    'start_date',
    'end_date',
    'price',
    'type',
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date'   => 'datetime',
  ];

  protected $with = [
    'renter',
    'agent',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function renter()
  {
    return $this->belongsTo(Client::class, 'client_id');
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class, 'agent_id');
  }

  public function rules()
  {
    return [
      'property_id' => 'required|exists:properties,id',
      'agent_id'    => 'required|exists:agents,id',
      'client_id'   => 'nullable|exists:clients,id',
      'start_date'  => 'required|date',
      'end_date'    => 'required|date|after:start_date',
      'price'       => 'required|numeric|min:0',
      'type' => [
        'required',
        'string',
        Rule::in(array_column(RENTAL_TYPE::cases(), 'value'))
      ],
    ];
  }
}
