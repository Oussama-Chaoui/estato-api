<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyDescription extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'content',
    'ordering',
  ];

  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id' => 'required|exists:properties,id',
      'content'     => 'required|string',
      'ordering'    => 'nullable|integer|min:0',
    ];
  }
}
