<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends CrudController
{
  protected $table = 'locations';
  protected $restricted = ['create', 'update', 'delete'];

  protected $modelClass = Location::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  public function readAllCitiesWithRegions(Request $request)
  {
    try {
      $query = City::with('region');

      if ($request->input('per_page', 50) === 'all') {
        $items = $query->get();

        return response()->json(
          [
            'success' => true,
            'data' => [
              'items' => $items,
              'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'total_items' => $items->count(),
              ],
            ],
          ]
        );
      } else {
        $paginator = $query->paginate($request->input('per_page', 50));

        return response()->json(
          [
            'success' => true,
            'data' => [
              'items' => $paginator->items(),
              'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total_items' => $paginator->total(),
              ],
            ],
          ]
        );
      }
    } catch (\Exception $e) {
      Log::error('Error caught in function LocationController.readAllCitiesWithRegions: ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }
}
