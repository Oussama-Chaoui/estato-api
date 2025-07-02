<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
  public function run()
  {
    Tag::insert([
      ['name' => 'Casablanca',              'slug' => 'casablanca'],
      ['name' => 'Rabat',                   'slug' => 'rabat'],
      ['name' => 'Marrakech',               'slug' => 'marrakech'],
      ['name' => 'Tangier',                 'slug' => 'tangier'],
      ['name' => 'Agadir',                  'slug' => 'agadir'],
      ['name' => 'Fez',                     'slug' => 'fez'],
      ['name' => 'Chefchaouen',             'slug' => 'chefchaouen'],
      ['name' => 'Mortgage Rates',          'slug' => 'mortgage-rates'],
      ['name' => 'Property Tax',            'slug' => 'property-tax'],
      ['name' => 'Legal Advice',            'slug' => 'legal-advice'],
      ['name' => 'Home Staging',            'slug' => 'home-staging'],
      ['name' => 'Interior Design',         'slug' => 'interior-design'],
      ['name' => 'Energy Efficiency',       'slug' => 'energy-efficiency'],
      ['name' => 'Real Estate Investment',  'slug' => 'real-estate-investment'],
      ['name' => 'Rental Yield',            'slug' => 'rental-yield'],
      ['name' => 'First-Time Buyer',        'slug' => 'first-time-buyer'],
      ['name' => 'Luxury Villa',            'slug' => 'luxury-villa'],
      ['name' => 'Studio Apartment',        'slug' => 'studio-apartment'],
      ['name' => 'Commercial Space',        'slug' => 'commercial-space'],
      ['name' => 'Vacation Home',           'slug' => 'vacation-home'],
    ]);
  }
}
