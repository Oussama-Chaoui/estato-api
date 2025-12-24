<?php

namespace App\Http\Controllers;

use App\Enums\PROPERTY_STATUS;
use App\Enums\WEBSITE_FOCUS;
use App\Models\Location;
use App\Models\Property;
use App\Models\PropertyRental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Log;

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

  // Override the base query to include sold properties for admin purposes
  protected function getReadAllQuery(): \Illuminate\Database\Eloquent\Builder
  {
    return $this->model()->withSold();
  }

  protected function afterReadOne($item, Request $request)
  {
    $item->load('rentals');
  }

  public function createOne(Request $request)
  {
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

        // Handle location with new structure
        $cityId = $request->input('location.city_id');
        $streetAddress = $request->input('location.street_address');
        $latitude = $request->input('location.latitude');
        $longitude = $request->input('location.longitude');

        // Create location if city is provided
        if ($cityId) {
          $location = Location::create([
            'city_id' => $cityId,
            'street_address' => $streetAddress,
            'latitude' => $latitude,
            'longitude' => $longitude,
          ]);
          $validated['location_id'] = $location->id;
        }

        $agentIds   = $request->input('agent_ids', []);
        $amenityIds = $request->input('amenity_ids', []);

        $featureData = $request->input('features', []);

        // Automatically set status based on sale price
        $salePrice = $request->input('sale_price', 0);
        if ($salePrice > 0) {
          $validated['status'] = PROPERTY_STATUS::FOR_SALE;
        } else {
          $validated['status'] = PROPERTY_STATUS::FOR_RENT;
        }

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
      Log::error('PropertyController::createOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function updateOne($id, Request $request)
  {
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

        // Validate images array to ensure all image_id values exist in uploads table
        $images = $request->input('images', []);
        if (!empty($images)) {
          $imageIds = collect($images)->pluck('image_id')->toArray();
          $existingUploadIds = \App\Models\Upload::whereIn('id', $imageIds)->pluck('id')->toArray();
          $missingIds = array_diff($imageIds, $existingUploadIds);

          if (!empty($missingIds)) {
            return response()->json([
              'success' => false,
              'errors'  => [__('properties.image_ids_not_exist', ['ids' => implode(', ', $missingIds)])],
            ]);
          }
        }

        // 3) Handle location with new structure
        $cityId = $request->input('location.city_id');
        $streetAddress = $request->input('location.street_address');
        $latitude = $request->input('location.latitude');
        $longitude = $request->input('location.longitude');

        // 4) Gather pivot data
        $agentIds   = $request->input('agent_ids', []);
        $amenityIds = $request->input('amenity_ids', []);
        $featureData = $request->only([
          'bedrooms',
          'bathrooms',
          'area',
          'garages',
          'floors',
        ]);

        // Automatically set status based on sale price
        $salePrice = $request->input('sale_price', 0);
        if ($salePrice > 0) {
          $validated['status'] = PROPERTY_STATUS::FOR_SALE;
        } else {
          $validated['status'] = PROPERTY_STATUS::FOR_RENT;
        }

        // 5) Find & update the model
        $property = Property::findOrFail($id);

        // Handle location update
        if ($cityId) {
          $locationId = $request->input('location_id');

          if ($locationId) {
            // Update existing location using locationId
            $existingLocation = Location::findOrFail($locationId);
            $existingLocation->update([
              'city_id' => $cityId,
              'street_address' => $streetAddress,
              'latitude' => $latitude,
              'longitude' => $longitude,
            ]);
            $validated['location_id'] = $existingLocation->id;
          } else {
            // Create new location
            $location = Location::create([
              'city_id' => $cityId,
              'street_address' => $streetAddress,
              'latitude' => $latitude,
              'longitude' => $longitude,
            ]);
            $validated['location_id'] = $location->id;
          }
        }

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
          ->delete();

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
      Log::error('PropertyController::updateOne error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function readPropertyAvailability($propertyId, Request $request)
  {
    try {
      $property = Property::findOrFail($propertyId);

      $year  = (int) $request->query('year',  Carbon::now()->year);
      $month = (int) $request->query('month', Carbon::now()->month);

      $windowStart = Carbon::create($year, $month, 1)->startOfDay();
      $windowEnd   = (clone $windowStart)->endOfMonth()->endOfDay();

      $rentals = PropertyRental::where('property_id', $property->id)
        ->where(function ($q) use ($windowStart, $windowEnd) {
          $q->whereBetween('start_date', [$windowStart, $windowEnd])
            ->orWhereBetween('end_date',   [$windowStart, $windowEnd])
            ->orWhere(function ($q2) use ($windowStart, $windowEnd) {
              $q2->where('start_date', '<', $windowStart)
                ->where('end_date',   '>', $windowEnd);
            });
        })
        ->get();

      $user = $request->user();
      $currentClientId = ($user && $user->client) ? $user->client->id : null;

      $availability = $rentals->map(function ($rental) use ($currentClientId) {
        $entry = [
          'start_date' => $rental->start_date->toISOString(),
          'end_date'   => $rental->end_date->toISOString(),
        ];

        if ($currentClientId !== null && $rental->client_id === $currentClientId) {
          $entry['client_id'] = $rental->client_id;
        }

        return $entry;
      });

      return response()->json([
        'success' => true,
        'data'    => [
          'year'         => $year,
          'month'        => $month,
          'availability' => $availability,
        ],
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      Log::error('PropertyController::readPropertyAvailability error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }

  public function searchByFilters(Request $request)
  {
    try {
      // Get pagination parameters from query string
      $page = $request->query('page', 1);
      $perPage = $request->query('per_page', 12);

      // Get all filter parameters from request body
      $location = $request->input('location');
      $checkIn = $request->input('check_in');
      $checkOut = $request->input('check_out');
      $availableFrom = $request->input('available_from');
      $propertyType = $request->input('property_type');
      $minPrice = $request->input('min_price');
      $maxPrice = $request->input('max_price');
      $minArea = $request->input('min_area');
      $maxArea = $request->input('max_area');
      $bedrooms = $request->input('bedrooms');
      $bathrooms = $request->input('bathrooms');
      $garages = $request->input('garages');
      $floors = $request->input('floors');
      $amenities = $request->input('amenities', []);
      $furnishingStatus = $request->input('furnishing_status');
      $websiteFocus = $request->input('website_focus');


      // Build the base query
      $query = Property::with(['location', 'agents', 'amenities', 'images', 'features']);

      // Filter by website focus
      if ($websiteFocus === WEBSITE_FOCUS::DAILY_RENT->value) {
        $query->where('daily_price_enabled', true);
      } elseif ($websiteFocus === WEBSITE_FOCUS::RENT->value) {
        $query->where('monthly_price_enabled', true);
      } elseif ($websiteFocus === WEBSITE_FOCUS::SELLING->value) {
        $query->where('sale_price', '>', 0);
      }
      // For ALL focus, no additional filtering is applied

      // Filter by location if provided (optimized with proper OR grouping)
      if ($location) {
        $query->where(function ($locationQuery) use ($location) {
          // Search in city names
          $locationQuery->whereHas('location.city', function ($cityQuery) use ($location) {
            $cityQuery->where(function ($nameQuery) use ($location) {
              $nameQuery->where('names->en', 'like', '%' . $location . '%')
                ->orWhere('names->fr', 'like', '%' . $location . '%')
                ->orWhere('names->es', 'like', '%' . $location . '%')
                ->orWhere('names->ar', 'like', '%' . $location . '%');
            });
          })
            // OR search in region names
            ->orWhereHas('location.city.region', function ($regionQuery) use ($location) {
              $regionQuery->where(function ($nameQuery) use ($location) {
                $nameQuery->where('names->en', 'like', '%' . $location . '%')
                  ->orWhere('names->fr', 'like', '%' . $location . '%')
                  ->orWhere('names->es', 'like', '%' . $location . '%')
                  ->orWhere('names->ar', 'like', '%' . $location . '%');
              });
            });
        });
      }

      // Filter by property type if provided
      if ($propertyType) {
        $query->where('type', $propertyType);
      }

      // Filter by price range if provided
      if ($minPrice !== null && $minPrice > 0) {
        if ($websiteFocus === WEBSITE_FOCUS::DAILY_RENT->value) {
          $query->where('daily_price', '>=', $minPrice);
        } elseif ($websiteFocus === WEBSITE_FOCUS::RENT->value) {
          $query->where('monthly_price', '>=', $minPrice);
        } elseif ($websiteFocus === WEBSITE_FOCUS::SELLING->value) {
          $query->where('sale_price', '>=', $minPrice);
        }
      }
      if ($maxPrice !== null && $maxPrice > 0) {
        if ($websiteFocus === WEBSITE_FOCUS::DAILY_RENT->value) {
          $query->where('daily_price', '<=', $maxPrice);
        } elseif ($websiteFocus === WEBSITE_FOCUS::RENT->value) {
          $query->where('monthly_price', '<=', $maxPrice);
        } elseif ($websiteFocus === WEBSITE_FOCUS::SELLING->value) {
          $query->where('sale_price', '<=', $maxPrice);
        }
      }

      // Filter by bedrooms if provided
      if ($bedrooms) {
        // Handle "5+" case - convert to integer 5
        $bedroomsValue = ($bedrooms === '5+' || $bedrooms === '5+') ? 5 : (int) $bedrooms;
        $query->whereHas('features', function ($q) use ($bedroomsValue) {
          $q->where('bedrooms', '>=', $bedroomsValue);
        });
      }

      // Filter by bathrooms if provided
      if ($bathrooms) {
        // Handle "5+" case - convert to integer 5
        $bathroomsValue = ($bathrooms === '5+' || $bathrooms === '5+') ? 5 : (int) $bathrooms;
        $query->whereHas('features', function ($q) use ($bathroomsValue) {
          $q->where('bathrooms', '>=', $bathroomsValue);
        });
      }

      // Filter by area range if provided (sale-specific)
      if ($minArea !== null && $minArea > 0) {
        $query->whereHas('features', function ($q) use ($minArea) {
          $q->where('area', '>=', $minArea);
        });
      }
      if ($maxArea !== null && $maxArea > 0) {
        $query->whereHas('features', function ($q) use ($maxArea) {
          $q->where('area', '<=', $maxArea);
        });
      }

      // Filter by garages if provided (sale-specific)
      if ($garages !== null) {
        $query->whereHas('features', function ($q) use ($garages) {
          $q->where('garages', '>=', $garages);
        });
      }

      // Filter by floors if provided (sale-specific)
      if ($floors !== null) {
        $query->whereHas('features', function ($q) use ($floors) {
          $q->where('floors', '>=', $floors);
        });
      }



      // Filter by amenities if provided (AND logic - properties must have ALL selected amenities)
      if (!empty($amenities)) {
        foreach ($amenities as $amenityId) {
          $query->whereHas('amenities', function ($q) use ($amenityId) {
            $q->where('amenities.id', $amenityId);
          });
        }
      }

      // Filter by furnishing status if provided
      if ($furnishingStatus) {
        $query->where('furnishing_status', $furnishingStatus);
      }

      // Filter by date availability
      if ($checkIn && $checkOut) {
        // Daily rental logic - check for conflicting rentals
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->endOfDay();

        // Get properties that don't have conflicting rentals
        $query->whereDoesntHave('rentals', function ($q) use ($checkInDate, $checkOutDate) {
          $q->where(function ($subQ) use ($checkInDate, $checkOutDate) {
            // Check for overlapping rentals
            $subQ->where(function ($overlapQ) use ($checkInDate, $checkOutDate) {
              $overlapQ->where('start_date', '<=', $checkOutDate)
                ->where('end_date', '>=', $checkInDate);
            });
          });
        });
      } elseif ($availableFrom) {
        // Monthly rental logic - assume 2 months availability from start date
        $startDate = Carbon::parse($availableFrom)->startOfDay();
        $endDate = Carbon::parse($availableFrom)->addMonths(2)->endOfDay();

        // Get properties that don't have conflicting rentals for the 2-month period
        $query->whereDoesntHave('rentals', function ($q) use ($startDate, $endDate) {
          $q->where(function ($subQ) use ($startDate, $endDate) {
            // Check for overlapping rentals
            $subQ->where(function ($overlapQ) use ($startDate, $endDate) {
              $overlapQ->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate);
            });
          });
        });
      }

      // Sort: Featured properties first, then by newest (created_at desc)
      $query->orderBy('featured', 'desc')
        ->orderBy('created_at', 'desc');

      // Get paginated results
      $properties = $query->paginate($perPage, ['*'], 'page', $page);

      return response()->json([
        'success' => true,
        'data' => [
          'items' => $properties->items(),
          'meta' => [
            'total' => $properties->total(),
            'per_page' => $properties->perPage(),
            'current_page' => $properties->currentPage(),
            'last_page' => $properties->lastPage(),
          ],
        ],
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors'  => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      Log::error('PropertyController::searchByFilters error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors'  => [__('common.unexpected_error')],
      ]);
    }
  }
}
