<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $amenities = [
      ['name' => 'Swimming Pool',                     'icon' => 'pool'],
      ['name' => 'Gym',                                 'icon' => 'dumbbell'],
      ['name' => 'Parking',                             'icon' => 'parking'],
      ['name' => 'WiFi',                                'icon' => 'wifi'],
      ['name' => 'Air Conditioning',                    'icon' => 'air-conditioner'],
      ['name' => 'Elevator',                            'icon' => 'elevator'],
      ['name' => 'Security',                            'icon' => 'shield'],
      ['name' => 'Garden',                              'icon' => 'tree'],
      ['name' => 'Balcony',                             'icon' => 'balcony'],
      ['name' => 'Terrace',                             'icon' => 'terrace'],
      ['name' => 'Fireplace',                           'icon' => 'fireplace'],
      ['name' => 'Laundry Room',                        'icon' => 'washing-machine'],
      ['name' => 'Concierge',                           'icon' => 'bell'],
      ['name' => 'Doorman',                             'icon' => 'door'],
      ['name' => 'Pet Friendly',                        'icon' => 'paw'],
      ['name' => 'BBQ Area',                            'icon' => 'bbq'],
      ['name' => 'Spa',                                 'icon' => 'spa'],
      ['name' => 'Sauna',                               'icon' => 'sauna'],
      ['name' => 'Jacuzzi',                             'icon' => 'jacuzzi'],
      ['name' => 'Tennis Court',                        'icon' => 'tennis'],
      ['name' => 'Basketball Court',                    'icon' => 'basketball'],
      ['name' => 'Playground',                          'icon' => 'swing'],
      ['name' => 'Clubhouse',                           'icon' => 'home'],
      ['name' => 'Storage Room',                        'icon' => 'box'],
      ['name' => 'Bicycle Storage',                     'icon' => 'bicycle'],
      ['name' => 'Central Heating',                     'icon' => 'fire'],
      ['name' => 'Cable TV',                            'icon' => 'tv'],
      ['name' => 'Intercom',                            'icon' => 'intercom'],
      ['name' => 'Furnished',                           'icon' => 'sofa'],
      ['name' => 'Outdoor Kitchen',                     'icon' => 'kitchen'],
      ['name' => 'Rooftop Deck',                        'icon' => 'rooftop'],
      ['name' => 'Guest Suite',                         'icon' => 'bed'],
      ['name' => 'Private Entrance',                    'icon' => 'door-open'],
      ['name' => 'Solar Panels',                        'icon' => 'sun'],
      ['name' => 'Smart Home System',                   'icon' => 'smart'],
      ['name' => 'Home Automation',                     'icon' => 'automation'],
      ['name' => 'Wine Cellar',                         'icon' => 'wine'],
      ['name' => 'Library',                             'icon' => 'book'],
      ['name' => 'Media Room',                          'icon' => 'film'],
      ['name' => 'Study Room',                          'icon' => 'pencil'],
      ['name' => 'Indoor Pool',                         'icon' => 'pool'],
      ['name' => 'Garden Patio',                        'icon' => 'flower'],
      ['name' => 'Fire Alarm',                          'icon' => 'alarm'],
      ['name' => 'Carbon Monoxide Detector',            'icon' => 'co2'],
      ['name' => 'Backup Generator',                    'icon' => 'generator'],
      ['name' => 'Security Cameras',                    'icon' => 'camera'],
      ['name' => 'Soundproofing',                       'icon' => 'sound'],
      ['name' => 'High-Speed Internet',                 'icon' => 'internet'],
      ['name' => 'Electric Vehicle Charging Station',   'icon' => 'charging-station'],
      ['name' => 'Valet Parking',                       'icon' => 'car'],
    ];

    foreach ($amenities as $amenity) {
      DB::table('amenities')->updateOrInsert(
        ['name' => $amenity['name']],
        [
          'icon'       => $amenity['icon'],
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );
    }
  }
}
