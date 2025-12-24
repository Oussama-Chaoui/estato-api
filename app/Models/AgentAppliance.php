<?php

namespace App\Models;

use App\Enums\AGENT_APPLICATION_STATUS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class AgentAppliance extends BaseModel
{
  use HasFactory;

  protected $table = 'agents_appliances';

  protected $fillable = [
    'name',
    'email',
    'phone',
    'status',
  ];

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'name' => 'required|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'required|string|max:20',
      'status' => [
        'sometimes',
        'string',
        Rule::in(array_column(AGENT_APPLICATION_STATUS::cases(), 'value'))
      ],
    ];
  }
}
