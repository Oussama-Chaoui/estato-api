<?php

namespace App\Models;

use App\Enums\LANGUAGE as LANGUAGE_ENUM;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Language extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'name'
  ];

  public function agents()
  {
    return $this->belongsToMany(Agent::class, 'agents_languages');
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    $rules = [
      'name' => [
        'required',
        'string',
        'unique:languages,name' . ($id ? ',' . $id : ''),
        Rule::in(array_column(LANGUAGE_ENUM::cases(), 'value'))
      ],
    ];

    return $rules;
  }
}
