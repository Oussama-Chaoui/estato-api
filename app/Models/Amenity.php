<?php

namespace App\Models;

use App\Models\Classes\DataTableParams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Amenity extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'name',
    'icon',
  ];

  public function properties()
  {
    return $this->belongsToMany(Property::class, 'properties_amenities')->withPivot('notes');
  }

  public function scopeDataTable($query, DataTableParams $params)
  {
    \Log::info('DataTableParams:', [
      'checkPermission' => $params->checkPermission,
      'orderColumn' => $params->orderColumn,
      'orderDir' => $params->orderDir,
      'filterParam' => $params->filterParam,
    ]);

    if ($params->hasOrderParam()) {
      $query->dataTableSort($params->orderColumn, $params->orderDir);
    }

    if ($params->hasFilterParam()) {
      $this->filter($query, $params->filterParam);
    }

    return $query;
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    return [
      'name' => [
        'required',
        'string',
        Rule::unique('amenities', 'name')->ignore($id),
      ],
      'icon' => 'nullable|string|max:255',
    ];
  }
}
