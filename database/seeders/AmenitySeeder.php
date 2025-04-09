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
      ['name' => 'Swimming Pool',                     'icon' => 'pool'],                           // PoolIcon
      ['name' => 'Gym',                                 'icon' => 'fitness-center'],                 // FitnessCenterIcon
      ['name' => 'Parking',                             'icon' => 'local-parking'],                  // LocalParkingIcon
      ['name' => 'WiFi',                                'icon' => 'wifi'],                           // WifiIcon
      ['name' => 'Air Conditioning',                    'icon' => 'ac-unit'],                        // AcUnitIcon
      ['name' => 'Elevator',                            'icon' => 'elevator'],                       // ElevatorIcon (if available)
      ['name' => 'Security',                            'icon' => 'security'],                       // SecurityIcon
      ['name' => 'Garden',                              'icon' => 'emoji-nature'],                   // EmojiNatureIcon
      // For Balcony, since there is no dedicated MUI icon, you could choose a substitute (here “deck” is used)
      ['name' => 'Balcony',                             'icon' => 'deck'],                           // Custom mapping for Balcony
      // Terrace / Rooftop Deck – using Roofing icon
      ['name' => 'Terrace',                             'icon' => 'roofing'],                        // RoofingIcon
      ['name' => 'Fireplace',                           'icon' => 'fireplace'],                      // FireplaceIcon
      ['name' => 'Laundry Room',                        'icon' => 'local-laundry-service'],          // LocalLaundryServiceIcon
      ['name' => 'Concierge',                           'icon' => 'room-service'],                   // RoomServiceIcon
      ['name' => 'Doorman',                             'icon' => 'door-front'],                     // DoorFrontIcon
      ['name' => 'Pet Friendly',                        'icon' => 'pets'],                           // PetsIcon
      ['name' => 'BBQ Area',                            'icon' => 'outdoor-grill'],                  // OutdoorGrillIcon
      ['name' => 'Spa',                                 'icon' => 'spa'],                            // SpaIcon
      // Using HotTubIcon for both Sauna and Jacuzzi
      ['name' => 'Sauna',                               'icon' => 'hot-tub'],                        // HotTubIcon
      ['name' => 'Jacuzzi',                             'icon' => 'hot-tub'],                        // HotTubIcon
      ['name' => 'Tennis Court',                        'icon' => 'sports-tennis'],                  // SportsTennisIcon
      ['name' => 'Basketball Court',                    'icon' => 'sports-basketball'],              // SportsBasketballIcon
      ['name' => 'Playground',                          'icon' => 'child-friendly'],                 // ChildFriendlyIcon
      ['name' => 'Clubhouse',                           'icon' => 'home'],                           // HomeIcon
      ['name' => 'Storage Room',                        'icon' => 'warehouse'],                      // WarehouseIcon
      ['name' => 'Bicycle Storage',                     'icon' => 'directions-bike'],                // DirectionsBikeIcon
      ['name' => 'Central Heating',                     'icon' => 'whatshot'],                       // WhatshotIcon
      ['name' => 'Cable TV',                            'icon' => 'live-tv'],                        // LiveTvIcon
      // For Intercom, we use SupportAgentIcon as a close match
      ['name' => 'Intercom',                            'icon' => 'support-agent'],                  // SupportAgentIcon
      ['name' => 'Furnished',                           'icon' => 'weekend'],                        // WeekendIcon
      ['name' => 'Outdoor Kitchen',                     'icon' => 'kitchen'],                        // KitchenIcon
      ['name' => 'Rooftop Deck',                        'icon' => 'roofing'],                        // RoofingIcon
      ['name' => 'Guest Suite',                         'icon' => 'king-bed'],                       // KingBedIcon
      ['name' => 'Private Entrance',                    'icon' => 'meeting-room'],                   // MeetingRoomIcon
      ['name' => 'Solar Panels',                        'icon' => 'wb-sunny'],                       // WbSunnyIcon
      // For Smart Home System, we can use SmartphoneIcon as a proxy
      ['name' => 'Smart Home System',                   'icon' => 'smartphone'],                     // SmartphoneIcon
      ['name' => 'Home Automation',                     'icon' => 'settings-remote'],                // SettingsRemoteIcon
      ['name' => 'Wine Cellar',                         'icon' => 'wine-bar'],                       // WineBarIcon
      ['name' => 'Library',                             'icon' => 'menu-book'],                      // MenuBookIcon
      ['name' => 'Media Room',                          'icon' => 'theaters'],                       // TheatersIcon
      ['name' => 'Study Room',                          'icon' => 'school'],                         // SchoolIcon
      ['name' => 'Indoor Pool',                         'icon' => 'pool'],                           // PoolIcon
      // For Garden Patio, using DeckIcon again as a substitute
      ['name' => 'Garden Patio',                        'icon' => 'deck'],                           // Custom mapping for Garden Patio
      ['name' => 'Fire Alarm',                          'icon' => 'alarm'],                          // AlarmIcon
      ['name' => 'Carbon Monoxide Detector',            'icon' => 'co2'],                            // Co2Icon
      ['name' => 'Backup Generator',                    'icon' => 'battery-charging-full'],          // BatteryChargingFullIcon
      ['name' => 'Security Cameras',                    'icon' => 'videocam'],                       // VideocamIcon
      ['name' => 'Soundproofing',                       'icon' => 'volume-off'],                     // VolumeOffIcon
      ['name' => 'High-Speed Internet',                 'icon' => 'speed'],                          // SpeedIcon
      ['name' => 'Electric Vehicle Charging Station',   'icon' => 'ev-station'],                     // EvStationIcon
      ['name' => 'Valet Parking',                       'icon' => 'local-taxi'],                     // LocalTaxiIcon
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
