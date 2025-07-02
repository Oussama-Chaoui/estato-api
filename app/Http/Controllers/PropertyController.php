<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PropertyController extends CrudController
{
  protected $table = 'properties';

  protected $modelClass = Property::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  protected $restricted = ['create', 'update', 'delete'];

  protected function afterReadOne($item, Request $request)
  {
    $item->load('rentals');
  }

  public function createOne(Request $request)
  {
    \Log::info('PropertyController::createOne request data: ', $request->all());
    try {
      return DB::transaction(function () use ($request) {
        $user = $request->user();
        if (! $user->hasPermission('properties', 'create')) {
          return response()->json([
            'success' => false,
            'errors'  => [__('common.permission_denied')],
          ]);
        }

        $proto = new Property;
        $validated = $request->validate(
          $proto->rules(),
          method_exists($proto, 'validationMessages')
            ? $proto->validationMessages()
            : []
        );

        $lat    = $request->input('latitude');
        $lng    = $request->input('longitude');
        $region = $request->input('region', '');
        $city   = $request->input('city', '');

        if ($lat !== null && $lng !== null) {
          $location = Location::firstOrCreate(
            ['latitude'  => $lat, 'longitude' => $lng],
            ['region'    => $region, 'city'      => $city]
          );
          $validated['location_id'] = $location->id;
        }

        $agentIds   = $request->input('agent_ids', []);
        $amenityIds = $request->input('amenity_ids', []);

        $featureData = $request->only([
          'bedrooms',
          'bathrooms',
          'area',
          'garages',
          'floors',
          'pool',
          'garden',
        ]);

        $property = Property::create($validated);

        $property->agents()->sync($agentIds);
        $property->amenities()->sync($amenityIds);

        $property->features()->create($featureData);


        $imagePayload = collect($request->input('images', []))
          ->map(fn(array $img) => [
            'image_id' => $img['image_id'],
            'ordering' => $img['ordering'] ?? null,
            'caption'  => $img['caption']  ?? null,
          ])
          ->all();

        if (count($imagePayload)) {
          $property->images()->createMany($imagePayload);
        }


        if (method_exists($this, 'afterCreateOne')) {
          $this->afterCreateOne($property, $request);
        }

        $property->load(['location', 'agents', 'amenities', 'features']);

        return response()->json([
          'success' => true,
          'data'    => ['item' => $property],
          'message' => __('properties.created'),
        ]);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      \Log::error('PropertyController::createOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function updateOne($id, Request $request)
  {
    \Log::info('PropertyController::updateOne request data: ', $request->all());

    try {
      return DB::transaction(function () use ($id, $request) {
        // 1) Permission check
        $user = $request->user();
        if (! $user->hasPermission('properties', 'update', $id)) {
          return response()->json([
            'success' => false,
            'errors'  => [__('common.permission_denied')],
          ]);
        }

        // 2) Validation
        $proto = new Property;
        $validated = $request->validate(
          $proto->rules($id),
          method_exists($proto, 'validationMessages')
            ? $proto->validationMessages()
            : []
        );

        // 3) Handle location (lat/lng => firstOrCreate)
        $lat    = $request->input('latitude');
        $lng    = $request->input('longitude');
        $region = $request->input('region', '');
        $city   = $request->input('city', '');
        if ($lat !== null && $lng !== null) {
          $location = Location::firstOrCreate(
            ['latitude'  => $lat, 'longitude' => $lng],
            ['region'    => $region, 'city'      => $city]
          );
          $validated['location_id'] = $location->id;
        }

        // 4) Gather pivot data
        $agentIds   = $request->input('agent_ids', []);
        $amenityIds = $request->input('amenity_ids', []);
        $featureData = $request->only([
          'bedrooms',
          'bathrooms',
          'area',
          'garages',
          'floors',
          'pool',
          'garden',
        ]);

        // 5) Find & update the model
        $property = Property::findOrFail($id);
        $property->update($validated);

        // 6) Sync relations
        $property->agents()->sync($agentIds);
        $property->amenities()->sync($amenityIds);

        // 7) Features — update existing or create new
        $existingFeature = $property->features()->first();
        if ($existingFeature) {
          $existingFeature->update($featureData);
        } else {
          $property->features()->create($featureData);
        }

        $incoming = collect($request->input('images', []))
          ->map(fn(array $img) => [
            'image_id' => $img['image_id'],
            'ordering' => $img['ordering'] ?? 0,
            'caption'  => $img['caption']  ?? null,
          ]);

        $newImageIds = $incoming->pluck('image_id')->all();

        // 1) Delete only those PropertyImage rows whose image_id is NOT in the payload.
        //    The model hook will fire and delete the underlying Upload for each.
        $property->images()
          ->whereNotIn('image_id', $newImageIds)
          ->get()
          ->each
          ->delete();  // <-- triggers PropertyImage::deleting

        // 2) Upsert the remaining ones: update existing, create new
        foreach ($incoming as $img) {
          $existing = $property
            ->images()
            ->where('image_id', $img['image_id'])
            ->first();

          if ($existing) {
            // update ordering/caption
            $existing->update([
              'ordering' => $img['ordering'],
              'caption'  => $img['caption'],
            ]);
          } else {
            // brand-new image on this property
            $property->images()->create($img);
          }
        }

        $property->load(['location', 'agents', 'amenities', 'features', 'images']);

        return response()->json([
          'success' => true,
          'data'    => ['item' => $property],
          'message' => __('properties.updated'),
        ]);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      \Log::error('PropertyController::updateOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }
}
