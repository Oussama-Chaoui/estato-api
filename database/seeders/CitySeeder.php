<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the JSON file
        $jsonPath = database_path('seeders/data/cloud/locations.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        $id = 1; // Start city IDs from 1
        $usedSlugs = []; // Track used slugs to handle duplicates
        
        foreach ($data['cities']['data'] as $cityData) {
            $baseSlug = Str::slug($cityData['names']['en']);
            $slug = $baseSlug;
            $counter = 1;
            
            // Handle duplicate slugs by adding a number suffix
            while (in_array($slug, $usedSlugs)) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $usedSlugs[] = $slug;
            
            City::create([
                'id' => $id++,
                'region_id' => $cityData['region_id'],
                'names' => $cityData['names'],
                'slug' => $slug,
                'description' => null,
                'latitude' => null,
                'longitude' => null,
            ]);
        }
    }
}