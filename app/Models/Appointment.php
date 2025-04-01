<?php

namespace App\Models;

use App\Enums\APPOINTMENT_STATUS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Appointment extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'user_id',
    'agent_id',
    'scheduled_at',
    'status',
    'notes',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id'  => 'required|exists:properties,id',
      'user_id'      => 'nullable|exists:users,id',
      'agent_id'     => 'required|exists:agents,id',
      'scheduled_at' => 'required|date',
      'status'       => [
        'required',
        'string',
        Rule::in(array_column(APPOINTMENT_STATUS::cases(), 'value')),
      ],
      'notes'        => 'nullable|string',
    ];
  }
}
