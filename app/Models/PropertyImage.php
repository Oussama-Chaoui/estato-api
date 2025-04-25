<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyImage extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'property_id',
    'image_id',
    'ordering',
    'caption',
  ];

  protected $with = [
    'upload',
  ];

  /**
   * Get the property associated with this image.
   */
  public function property()
  {
    return $this->belongsTo(Property::class);
  }

  /**
   * Get the upload record associated with this image.
   */
  public function upload()
  {
    return $this->belongsTo(Upload::class, 'image_id');
  }

  protected static function booted()
  {
    parent::booted();

    static::deleting(function (PropertyImage $pi) {
      if ($pi->upload) {
        $pi->upload->delete();
      }
    });
  }

  function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'property_id' => 'required|exists:properties,id',
      'image_id'    => 'required|exists:uploads,id',
      'ordering'    => 'nullable|integer|min:0',
      'caption'     => 'nullable|string|max:255',
    ];
  }
}
