<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Location;
use App\Models\Property;
use App\Enums\PROPERTY_TYPE;
use App\Enums\PROPERTY_STATUS;
use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $location = Location::where('city', 'Tangier')->first();

    $properties = [
      [
        'title'          => 'Luxurious Apartment in Tangier - For Sale 1',
        'street_address' => '123 Rue de la Kasbah, Tangier, Morocco',
        'description'    => 'A beautiful apartment located in the heart of Tangier with stunning views of the Mediterranean Sea. For sale option.',
        'price'          => 1500000.00,
        'currency'       => 'MAD',
        'year_built'     => 2010,
        'lot_size'       => 120,
        'type'  => PROPERTY_TYPE::APARTMENT->value,
        'status'         => PROPERTY_STATUS::FOR_SALE->value,
        'has_vr'         => false,
      ],
      [
        'title'          => 'Modern Apartment in Tangier - For Sale 2',
        'street_address' => '124 Rue de la Kasbah, Tangier, Morocco',
        'description'    => 'A modern apartment with all the amenities you need. For sale option.',
        'price'          => 1700000.00,
        'currency'       => 'MAD',
        'year_built'     => 2012,
        'lot_size'       => 130,
        'type'  => PROPERTY_TYPE::APARTMENT->value,
        'status'         => PROPERTY_STATUS::FOR_SALE->value,
        'has_vr'         => false,
      ],
      [
        'title'          => 'Cozy Apartment in Tangier - For Rent 1',
        'street_address' => '125 Rue de la Kasbah, Tangier, Morocco',
        'description'    => 'A cozy apartment available for rent in a quiet neighborhood. For rent option.',
        'price'          => 8000.00,
        'currency'       => 'MAD',
        'year_built'     => 2015,
        'lot_size'       => 100,
        'type'  => PROPERTY_TYPE::APARTMENT->value,
        'status'         => PROPERTY_STATUS::FOR_RENT->value,
        'has_vr'         => false,
      ],
      [
        'title'          => 'Spacious Apartment in Tangier - For Rent 2',
        'street_address' => '126 Rue de la Kasbah, Tangier, Morocco',
        'description'    => 'A spacious apartment available for rent, ideal for professionals and families. For rent option.',
        'price'          => 9000.00,
        'currency'       => 'MAD',
        'year_built'     => 2018,
        'lot_size'       => 110,
        'type'  => PROPERTY_TYPE::APARTMENT->value,
        'status'         => PROPERTY_STATUS::FOR_RENT->value,
        'has_vr'         => false,
      ],
    ];

    foreach ($properties as $data) {
      $data['location_id'] = $location->id;
      $property = Property::firstOrCreate(['title' => $data['title']], $data);

      // Attach agents to the property based on its title and status.
      if ($property->status === PROPERTY_STATUS::FOR_SALE->value) {
        if ($property->title === 'Luxurious Apartment in Tangier - For Sale 1') {
          $agent = Agent::where('licence_number', 'LIC-001')->first();
          if ($agent) {
            $property->agents()->syncWithoutDetaching([$agent->id]);
          }
        } elseif ($property->title === 'Modern Apartment in Tangier - For Sale 2') {
          $agent = Agent::where('licence_number', 'LIC-002')->first();
          if ($agent) {
            $property->agents()->syncWithoutDetaching([$agent->id]);
          }
        }
      } elseif ($property->status === PROPERTY_STATUS::FOR_RENT->value) {
        if ($property->title === 'Cozy Apartment in Tangier - For Rent 1') {
          $agent = Agent::where('licence_number', 'LIC-003')->first();
          if ($agent) {
            $property->agents()->syncWithoutDetaching([$agent->id]);
          }
        } elseif ($property->title === 'Spacious Apartment in Tangier - For Rent 2') {
          $agent1 = Agent::where('licence_number', 'LIC-001')->first();
          $agent2 = Agent::where('licence_number', 'LIC-003')->first();
          $agentIds = [];
          if ($agent1) {
            $agentIds[] = $agent1->id;
          }
          if ($agent2) {
            $agentIds[] = $agent2->id;
          }
          if (!empty($agentIds)) {
            $property->agents()->syncWithoutDetaching($agentIds);
          }
        }
      }

      // Add a property description for each property.
      DB::table('property_descriptions')->updateOrInsert(
        ['property_id' => $property->id, 'ordering' => 0],
        [
          'content'    => "Detailed description for {$property->title}",
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );

      // Add property features for each property with logical values (area in square meters).
      $features = [];
      if ($property->title === 'Luxurious Apartment in Tangier - For Sale 1') {
        $features = [
          'bedrooms'  => 3,
          'bathrooms' => 2,
          'area'      => 150,   // 150 m²
          'garages'   => 1,
          'floors'    => 1,
          'pool'      => true,
          'garden'    => false,
        ];
      } elseif ($property->title === 'Modern Apartment in Tangier - For Sale 2') {
        $features = [
          'bedrooms'  => 4,
          'bathrooms' => 3,
          'area'      => 200,   // 200 m²
          'garages'   => 2,
          'floors'    => 1,
          'pool'      => false,
          'garden'    => true,
        ];
      } elseif ($property->title === 'Cozy Apartment in Tangier - For Rent 1') {
        $features = [
          'bedrooms'  => 2,
          'bathrooms' => 1,
          'area'      => 70,    // 70 m²
          'garages'   => 0,
          'floors'    => 1,
          'pool'      => false,
          'garden'    => false,
        ];
      } elseif ($property->title === 'Spacious Apartment in Tangier - For Rent 2') {
        $features = [
          'bedrooms'  => 3,
          'bathrooms' => 2,
          'area'      => 120,   // 120 m²
          'garages'   => 1,
          'floors'    => 2,
          'pool'      => true,
          'garden'    => true,
        ];
      }

      if (!empty($features)) {
        DB::table('property_features')->updateOrInsert(
          ['property_id' => $property->id],
          array_merge($features, [
            'created_at' => now(),
            'updated_at' => now(),
          ])
        );
      }

      // Add amenities to the property.
      $amenitiesToAttach = [];
      if ($property->title === 'Luxurious Apartment in Tangier - For Sale 1') {
        $amenitiesToAttach = [
          'Swimming Pool',
          'Gym',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Balcony',
          'Terrace'
        ];
      } elseif ($property->title === 'Modern Apartment in Tangier - For Sale 2') {
        $amenitiesToAttach = [
          'Gym',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Elevator',
          'Garden',
          'Balcony'
        ];
      } elseif ($property->title === 'Cozy Apartment in Tangier - For Rent 1') {
        $amenitiesToAttach = [
          'WiFi',
          'Air Conditioning',
          'Parking',
          'Elevator'
        ];
      } elseif ($property->title === 'Spacious Apartment in Tangier - For Rent 2') {
        $amenitiesToAttach = [
          'Swimming Pool',
          'Gym',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Garden',
          'Balcony',
          'Terrace'
        ];
      }

      if (!empty($amenitiesToAttach)) {
        $amenityIds = Amenity::whereIn('name', $amenitiesToAttach)->pluck('id')->toArray();
        $property->amenities()->syncWithoutDetaching($amenityIds);
      }

      // Add a price history record for each property.
      // We'll use the property's current price and creation date (if available) as the initial record.
      $recordedAt = $property->created_at ?? now();
      DB::table('property_price_histories')->updateOrInsert(
        ['property_id' => $property->id, 'recorded_at' => $recordedAt],
        [
          'price'      => $property->price,
          'currency'   => $property->currency,
          'notes'      => 'Initial price record',
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );
    }
  }
}
