<?php

namespace App\Models;

use App\Enums\WEBSITE_FOCUS;

class Setting extends BaseModel
{
  public static $cacheKey = 'settings';

  protected $fillable = [
    'key',
    'value',
  ];

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');

    $rules = [
      'key' => 'required|string',
      'value' => 'required|string',
    ];

    if (request()->input('key') === 'website_focus') {
      $rules['value'] = 'required|string|in:' . implode(',', WEBSITE_FOCUS::values());
    }

    return $rules;
  }

  public static function getWebsiteFocus(): WEBSITE_FOCUS
  {
    $setting = self::where('key', 'website_focus')->first();

    if (!$setting) {
      $setting = self::create([
        'key' => 'website_focus',
        'value' => WEBSITE_FOCUS::ALL->value
      ]);
    }

    return WEBSITE_FOCUS::fromString($setting->value);
  }

  public static function setWebsiteFocus(WEBSITE_FOCUS $focus): bool
  {
    return self::updateOrCreate(
      ['key' => 'website_focus'],
      ['value' => $focus->value]
    )->exists;
  }
}
