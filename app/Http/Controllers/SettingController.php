<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends CrudController
{
  protected $table = 'settings';

  protected $modelClass = Setting::class;

  // Override to make all methods public (no authentication required)
  protected $restricted = [];

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  public function getWebsiteFocus(Request $request)
  {
    try {
      $websiteFocus = Setting::getWebsiteFocus();

      return response()->json([
        'success' => true,
        'data' => ['website_focus' => $websiteFocus->value]
      ]);
    } catch (\Exception $e) {
      \Log::error('getWebsiteFocus error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors' => [__('settings.website_focus_retrieval_failed')]
      ], 500);
    }
  }

  public function updateWebsiteFocus(Request $request)
  {
    try {
      $validated = $request->validate([
        'value' => 'required|string|in:' . implode(',', \App\Enums\WEBSITE_FOCUS::values())
      ]);

      $focus = \App\Enums\WEBSITE_FOCUS::fromString($validated['value']);
      Setting::setWebsiteFocus($focus);

      return response()->json([
        'success' => true,
        'data' => ['website_focus' => $focus->value],
        'message' => __('settings.website_focus_updated')
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => \Illuminate\Support\Arr::flatten($e->errors())
      ]);
    } catch (\Exception $e) {
      \Log::error('updateWebsiteFocus error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors' => [__('settings.website_focus_update_failed')]
      ], 500);
    }
  }
}
