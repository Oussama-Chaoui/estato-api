<?php

namespace App\Models;

use App\Enums\INQUIRY_STATUS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class Inquiry extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'user_id',
    'name',
    'email',
    'phone',
    'message',
    'status',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id' => 'required|exists:properties,id',
      'user_id'     => 'nullable|exists:users,id',
      'name'        => 'required|string|max:255',
      'email'       => 'required|email|max:255',
      'phone'       => 'required|string|max:20',
      'message'     => 'required|string',
      'status'      => [
        'required',
        'string',
        Rule::in(array_column(INQUIRY_STATUS::cases(), 'value')),
      ],
    ];
  }
}
