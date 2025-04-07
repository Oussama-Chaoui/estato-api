<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $jsonPath = storage_path('app/cloud/cities.json');

    if (!$jsonPath) {
      $this->command->error("Json file not found : {$jsonPath}");
      return;
    }

    $jsonData = file_get_contents($jsonPath);
    $locations = json_decode($jsonData, true);

    if (!$locations) {
      $this->command->error("Invalid JSON in file: {$jsonPath}");
      return;
    }

    foreach ($locations as $locationData) {
      $data = [
        'region' => $locationData['admin_name'] ?? null,
        'city' => $locationData['city'] ?? null,
        'latitude' => isset($locationData['lat']) ? (float)$locationData['lat'] : null,
        'longitude' => isset($locationData['lng']) ? (float)$locationData['lng'] : null,
      ];

      if ($data['region'] && $data['city']) {
        Location::updateOrCreate(
          [
            'region' => $data['region'],
            'city' => $data['city']
          ],
          $data
        );
      }
    }
  }
}
