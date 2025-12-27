<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Read the JSON file
    $jsonPath = database_path('seeders/data/cloud/locations.json');
    $data = json_decode(file_get_contents($jsonPath), true);

    $usedSlugs = []; // Track used slugs to handle duplicates

    foreach ($data['regions']['data'] as $regionData) {
      $baseSlug = Str::slug($regionData['names']['en']);
      $slug = $baseSlug;
      $counter = 1;

      // Handle duplicate slugs by adding a number suffix
      while (in_array($slug, $usedSlugs)) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
      }

      $usedSlugs[] = $slug;

      Region::create([
        'id' => $regionData['id'],
        'names' => $regionData['names'],
        'slug' => $slug,
        'description' => null,
      ]);
    }
  }
}
