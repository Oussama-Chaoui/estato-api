<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

class PriceHistory extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'price',
    'currency',
    'recorded_at',
    'notes',
  ];

  /**
   * Get the property associated with this price history record.
   */
  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  /**
   * Get the validation rules for a price history record.
   *
   * @param mixed $id Optional ID for update scenarios.
   * @return array
   */
  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id' => 'required|exists:properties,id',
      'price'       => 'required|numeric|min:0',
      'currency'    => 'required|string',
      'recorded_at' => 'required|date',
      'notes'       => 'nullable|string',
    ];
  }
}
