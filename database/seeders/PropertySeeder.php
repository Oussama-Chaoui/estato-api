<?php

namespace Database\Seeders;

use App\Enums\FURNISHING_STATUS;
use App\Models\Amenity;
use App\Models\Location;
use App\Models\Property;
use App\Enums\PROPERTY_TYPE;
use App\Enums\PROPERTY_STATUS;
use App\Enums\RENTAL_TYPE;
use App\Models\Agent;
use App\Models\Client;
use App\Models\PropertyRental;
use App\Models\Upload;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
  public function run(): void
  {

    $imageFiles = ['house_1.avif', 'house_2.avif', 'house_3.avif', 'house_4.avif', 'house_5.avif', 'house_6.avif'];
    $uploadIds = [];
    foreach ($imageFiles as $filename) {
      $upload = Upload::firstOrCreate(
        ['path' => "storage/{$filename}"],
        ['name' => $filename]
      );
      $uploadIds[] = $upload->id;
    }

    $marrakechCity = \App\Models\City::where('names->en', 'Marrakesh')->first();
    $casablancaCity = \App\Models\City::where('names->en', 'Casablanca')->first();
    $rabatCity = \App\Models\City::where('names->en', 'Rabat')->first();
    $agadirCity = \App\Models\City::where('names->en', 'Agadir')->first();
    $tangierCity = \App\Models\City::where('names->en', 'Tangier')->first();

    $properties = [
      [
        'property' => [
          'title' => [
            'en' => 'Luxury Villa with Sea View in Marrakech',
            'fr' => 'Villa de Luxe avec Vue Mer à Marrakech',
            'es' => 'Villa de Lujo con Vista al Mar en Marrakech',
            'ar' => 'فيلا فاخرة بإطلالة بحرية في مراكش'
          ],
          'description' => [
            'en' => 'Stunning luxury villa with panoramic sea views, featuring 4 bedrooms, 3 bathrooms, private pool, and modern amenities. Perfect for families seeking exclusivity and comfort.',
            'fr' => 'Magnifique villa de luxe avec vues panoramiques sur la mer, comprenant 4 chambres, 3 salles de bain, piscine privée et équipements modernes. Parfait pour les familles recherchant exclusivité et confort.',
            'es' => 'Impresionante villa de lujo con vistas panorámicas al mar, con 4 habitaciones, 3 baños, piscina privada y comodidades modernas. Perfecta para familias que buscan exclusividad y confort.',
            'ar' => 'فيلا فاخرة مذهلة بإطلالات بانورامية على البحر، تتضمن 4 غرف نوم، 3 حمامات، مسبح خاص ومرافق حديثة. مثالية للعائلات التي تبحث عن الخصوصية والراحة.'
          ],
          'sale_price'     => 85000.00,
          'monthly_price'  => 45000.00,
          'daily_price'    => 2500.00,
          'daily_price_enabled' => true,
          'monthly_price_enabled' => true,
          'currency'       => 'MAD',
          'year_built'     => 2020,
          'type'           => PROPERTY_TYPE::VILLA->value,
          'status'         => PROPERTY_STATUS::SOLD->value,
          'has_vr'         => true,
          'featured'       => true,
          'furnishing_status' => FURNISHING_STATUS::FURNISHED->value,
        ],
        'location' => [
          'street_address' => [
            'en' => 'Avenue Mohammed V, Hivernage, Marrakech',
            'fr' => 'Avenue Mohammed V, Hivernage, Marrakech',
            'es' => 'Avenida Mohammed V, Hivernage, Marrakech',
            'ar' => 'شارع محمد الخامس، حي هيفرناج'
          ],
        ],
      ],
      [
        'property' => [
          'title' => [
            'en' => 'Modern Penthouse in Casablanca Business District',
            'fr' => 'Penthouse Moderne dans le Quartier d\'Affaires de Casablanca',
            'es' => 'Ático Moderno en el Distrito Comercial de Casablanca',
            'ar' => 'بنتهاوس حديث في الحي التجاري بالدار البيضاء'
          ],
          'description' => [
            'en' => 'Sophisticated penthouse in the heart of Casablanca\'s business district. Features 3 bedrooms, 2.5 bathrooms, rooftop terrace, smart home technology, and 24/7 security.',
            'fr' => 'Penthouse sophistiqué au cœur du quartier d\'affaires de Casablanca. Comprend 3 chambres, 2,5 salles de bain, terrasse sur le toit, technologie domotique et sécurité 24h/24.',
            'es' => 'Ático sofisticado en el corazón del distrito comercial de Casablanca. Cuenta con 3 habitaciones, 2.5 baños, terraza en la azotea, tecnología de hogar inteligente y seguridad 24/7.',
            'ar' => 'بنتهاوس أنيق في قلب الحي التجاري بالدار البيضاء. يتضمن 3 غرف نوم، 2.5 حمام، تراس على السطح، تقنية المنزل الذكي وأمن على مدار الساعة.'
          ],
          'sale_price'     => 0,
          'monthly_price'  => 65000.00,
          'daily_price'    => 3500.00,
          'daily_price_enabled' => true,
          'monthly_price_enabled' => true,
          'currency'       => 'MAD',
          'year_built'     => 2022,
          'type'           => PROPERTY_TYPE::APARTMENT->value,
          'status'         => PROPERTY_STATUS::FOR_SALE->value,
          'has_vr'         => true,
          'featured'       => true,
          'furnishing_status' => FURNISHING_STATUS::FURNISHED->value,
        ],
        'location' => [
          'street_address' => [
            'en' => 'Boulevard Anfa, Maarif, Casablanca',
            'fr' => 'Boulevard Anfa, Maarif, Casablanca',
            'es' => 'Bulevar Anfa, Maarif, Casablanca',
            'ar' => 'شارع أنفا، المعاريف'
          ],
        ],
      ],
      [
        'property' => [
          'title' => [
            'en' => 'Cozy Studio in Rabat Medina',
            'fr' => 'Studio Confortable dans la Médina de Rabat',
            'es' => 'Estudio Acogedor en la Medina de Rabat',
            'ar' => 'استوديو مريح في المدينة القديمة بالرباط'
          ],
          'description' => [
            'en' => 'Charming studio apartment in the historic Medina of Rabat. Perfect for students or young professionals. Features modern amenities while preserving traditional Moroccan architecture.',
            'fr' => 'Charmant studio dans la médina historique de Rabat. Parfait pour les étudiants ou jeunes professionnels. Comprend des équipements modernes tout en préservant l\'architecture marocaine traditionnelle.',
            'es' => 'Encantador estudio en la medina histórica de Rabat. Perfecto para estudiantes o jóvenes profesionales. Cuenta con comodidades modernas preservando la arquitectura marroquí tradicional.',
            'ar' => 'استوديو ساحر في المدينة القديمة التاريخية بالرباط. مثالي للطلاب أو الشباب المهنيين. يتضمن مرافق حديثة مع الحفاظ على العمارة المغربية التقليدية.'
          ],
          'sale_price'     => 1800000.00,
          'monthly_price'  => 12000.00,
          'daily_price'    => 800.00,
          'daily_price_enabled' => true,
          'monthly_price_enabled' => true,
          'currency'       => 'MAD',
          'year_built'     => 2018,
          'type'           => PROPERTY_TYPE::STUDIO->value,
          'status'         => PROPERTY_STATUS::FOR_RENT->value,
          'has_vr'         => false,
          'featured'       => false,
          'furnishing_status' => FURNISHING_STATUS::SEMI_FURNISHED->value,
        ],
        'location' => [
          'street_address' => [
            'en' => 'Rue des Consuls, Medina, Rabat',
            'fr' => 'Rue des Consuls, Médina, Rabat',
            'es' => 'Calle de los Cónsules, Medina, Rabat',
            'ar' => 'شارع القناصل، المدينة القديمة'
          ],
        ],
      ],
      [
        'property' => [
          'title' => [
            'en' => 'Family Villa with Garden in Agadir',
            'fr' => 'Villa Familiale avec Jardin à Agadir',
            'es' => 'Villa Familiar con Jardín en Agadir',
            'ar' => 'فيلا عائلية مع حديقة في أغادير'
          ],
          'description' => [
            'en' => 'Spacious family villa with beautiful garden and mountain views. Features 5 bedrooms, 4 bathrooms, large kitchen, and outdoor dining area. Perfect for large families.',
            'fr' => 'Villa familiale spacieuse avec beau jardin et vues sur les montagnes. Comprend 5 chambres, 4 salles de bain, grande cuisine et espace de restauration extérieur. Parfait pour les grandes familles.',
            'es' => 'Villa familiar espaciosa con hermoso jardín y vistas a las montañas. Cuenta con 5 habitaciones, 4 baños, cocina grande y área de comedor exterior. Perfecta para familias numerosas.',
            'ar' => 'فيلا عائلية واسعة مع حديقة جميلة وإطلالات على الجبال. تتضمن 5 غرف نوم، 4 حمامات، مطبخ كبير ومنطقة طعام خارجية. مثالية للعائلات الكبيرة.'
          ],
          'sale_price'     => 6500000.00,
          'monthly_price'  => 35000.00,
          'daily_price'    => 2000.00,
          'daily_price_enabled' => true,
          'monthly_price_enabled' => true,
          'currency'       => 'MAD',
          'year_built'     => 2019,
          'type'           => PROPERTY_TYPE::COMMERCIAL->value,
          'status'         => PROPERTY_STATUS::FOR_RENT->value,
          'has_vr'         => true,
          'featured'       => true,
          'furnishing_status' => FURNISHING_STATUS::FURNISHED->value,
        ],
        'location' => [
          'street_address' => [
            'en' => 'Route de l\'Oued Souss, Talborjt, Agadir',
            'fr' => 'Route de l\'Oued Souss, Talborjt, Agadir',
            'es' => 'Ruta de Oued Souss, Talborjt, Agadir',
            'ar' => 'طريق واد سوس، تالبورجت'
          ],
        ],
      ],
      [
        'property' => [
          'title' => [
            'en' => 'Traditional Moroccan House in Tangier Medina',
            'fr' => 'Maison Marocaine Traditionnelle dans la Médina de Tanger',
            'es' => 'Casa Marroquí Tradicional en la Medina de Tánger',
            'ar' => 'بيت مغربي تقليدي في المدينة القديمة بطنجة'
          ],
          'description' => [
            'en' => 'Beautiful traditional Moroccan house in the heart of Tangier\'s historic Medina. Features 3 bedrooms, 2 bathrooms, traditional courtyard, and authentic Moroccan architecture. Perfect for those seeking authentic cultural experience.',
            'fr' => 'Belle maison marocaine traditionnelle au cœur de la médina historique de Tanger. Comprend 3 chambres, 2 salles de bain, cour traditionnelle et architecture marocaine authentique. Parfait pour ceux qui recherchent une expérience culturelle authentique.',
            'es' => 'Hermosa casa marroquí tradicional en el corazón de la medina histórica de Tánger. Cuenta con 3 habitaciones, 2 baños, patio tradicional y arquitectura marroquí auténtica. Perfecta para quienes buscan una experiencia cultural auténtica.',
            'ar' => 'بيت مغربي تقليدي جميل في قلب المدينة القديمة التاريخية بطنجة. يتضمن 3 غرف نوم، 2 حمام، فناء تقليدي وعمارة مغربية أصيلة. مثالي للذين يبحثون عن تجربة ثقافية أصيلة.'
          ],
          'sale_price'     => 4200000.00,
          'monthly_price'  => 28000.00,
          'daily_price'    => 1500.00,
          'daily_price_enabled' => true,
          'monthly_price_enabled' => true,
          'currency'       => 'MAD',
          'year_built'     => 2015,
          'type'           => PROPERTY_TYPE::HOUSE->value,
          'status'         => PROPERTY_STATUS::FOR_SALE->value,
          'has_vr'         => false,
          'featured'       => false,
          'furnishing_status' => FURNISHING_STATUS::SEMI_FURNISHED->value,
        ],
        'location' => [
          'street_address' => [
            'en' => 'Rue de la Kasbah, Medina, Tangier',
            'fr' => 'Rue de la Kasbah, Médina, Tanger',
            'es' => 'Calle de la Kasbah, Medina, Tánger',
            'ar' => 'شارع القصبة، المدينة القديمة'
          ],
        ],
      ],
    ];

    foreach ($properties as $index => $item) {
      $city = null;
      if ($index === 0) {
        $city = $marrakechCity;
      } elseif ($index === 1) {
        $city = $casablancaCity;
      } elseif ($index === 2) {
        $city = $rabatCity;
      } elseif ($index === 3) {
        $city = $agadirCity;
      } elseif ($index === 4) {
        $city = $tangierCity;
      }

      if (!$city) {
        continue;
      }

      $coordinates = [
        'Marrakesh' => [31.6295, -7.9811],
        'Casablanca' => [33.5731, -7.5898],
        'Rabat' => [34.0209, -6.8416],
        'Agadir' => [30.4278, -9.5981],
        'Tangier' => [35.7595, -5.8340],
      ];

      $cityName = $city->names['en'];
      $baseCoords = $coordinates[$cityName] ?? [31.6295, -7.9811];

      $location = Location::create([
        'city_id' => $city->id,
        'street_address' => $item['location']['street_address'],
        'latitude' => $baseCoords[0] + (rand(-10, 10) / 100),
        'longitude' => $baseCoords[1] + (rand(-10, 10) / 100),
      ]);

      $propertyData = $item['property'];
      $propertyData['location_id'] = $location->id;
      $englishTitle = $propertyData['title']['en'];
      $property = Property::firstOrCreate(['title->en' => $englishTitle], $propertyData);

      if ($property->title['en'] === 'Luxury Villa with Sea View in Marrakech') {
        $agent = Agent::where('licence_number', 'LIC-001')->first();
        if ($agent) {
          $property->agents()->syncWithoutDetaching([$agent->id]);
        }
      }
      if ($property->title['en'] === 'Modern Penthouse in Casablanca Business District') {
        $agent = Agent::where('licence_number', 'LIC-002')->first();
        if ($agent) {
          $property->agents()->syncWithoutDetaching([$agent->id]);
        }
      }
      if ($property->title['en'] === 'Cozy Studio in Rabat Medina') {
        $agent = Agent::where('licence_number', 'LIC-003')->first();
        if ($agent) {
          $property->agents()->syncWithoutDetaching([$agent->id]);

          $client = Client::whereHas('user', function ($query) {
            $query->where('email', 'client@example.com');
          })->first();
        }
      }
      if ($property->title['en'] === 'Family Villa with Garden in Agadir') {
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
      if ($property->title['en'] === 'Traditional Moroccan House in Tangier Medina') {
        $agent = Agent::where('licence_number', 'LIC-002')->first();
        if ($agent) {
          $property->agents()->syncWithoutDetaching([$agent->id]);
        }
      }

      DB::table('property_descriptions')->updateOrInsert(
        ['property_id' => $property->id, 'ordering' => 0],
        [
          'content'    => json_encode([
            'en' => "Detailed description for {$property->title['en']}",
            'fr' => "Description détaillée pour {$property->title['fr']}",
            'es' => "Descripción detallada para {$property->title['es']}",
            'ar' => "وصف مفصل لـ {$property->title['ar']}"
          ]),
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );

      $features = [];
      if ($property->title['en'] === 'Luxury Villa with Sea View in Marrakech') {
        $features = [
          'bedrooms'  => 4,
          'bathrooms' => 3,
          'area'      => 280,
          'garages'   => 2,
          'floors'    => 2,
        ];
      } elseif ($property->title['en'] === 'Modern Penthouse in Casablanca Business District') {
        $features = [
          'bedrooms'  => 3,
          'bathrooms' => 2,
          'area'      => 180,
          'garages'   => 1,
          'floors'    => 1,
        ];
      } elseif ($property->title['en'] === 'Cozy Studio in Rabat Medina') {
        $features = [
          'bedrooms'  => 1,
          'bathrooms' => 1,
          'area'      => 45,
          'garages'   => 0,
          'floors'    => 1,
        ];
      } elseif ($property->title['en'] === 'Family Villa with Garden in Agadir') {
        $features = [
          'bedrooms'  => 5,
          'bathrooms' => 4,
          'area'      => 350,
          'garages'   => 2,
          'floors'    => 2,
        ];
      } elseif ($property->title['en'] === 'Traditional Moroccan House in Tangier Medina') {
        $features = [
          'bedrooms'  => 3,
          'bathrooms' => 2,
          'area'      => 120,
          'garages'   => 0,
          'floors'    => 2,
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

      $amenitiesToAttach = [];
      if ($property->title['en'] === 'Luxury Villa with Sea View in Marrakech') {
        $amenitiesToAttach = [
          'Swimming Pool',
          'Gym',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Balcony',
          'Terrace',
          'Garden',
          'Security System'
        ];
      } elseif ($property->title['en'] === 'Modern Penthouse in Casablanca Business District') {
        $amenitiesToAttach = [
          'Gym',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Elevator',
          'Security System',
          'Smart Home',
          'Rooftop Terrace'
        ];
      } elseif ($property->title['en'] === 'Cozy Studio in Rabat Medina') {
        $amenitiesToAttach = [
          'WiFi',
          'Air Conditioning',
          'Kitchen',
          'Balcony'
        ];
      } elseif ($property->title['en'] === 'Family Villa with Garden in Agadir') {
        $amenitiesToAttach = [
          'Swimming Pool',
          'Garden',
          'Parking',
          'WiFi',
          'Air Conditioning',
          'Terrace',
          'BBQ Area',
          'Playground'
        ];
      } elseif ($property->title['en'] === 'Traditional Moroccan House in Tangier Medina') {
        $amenitiesToAttach = [
          'WiFi',
          'Air Conditioning',
          'Traditional Courtyard',
          'Balcony',
          'Kitchen',
          'Security System',
          'Traditional Architecture'
        ];
      }

      if (!empty($amenitiesToAttach)) {
        $amenityIds = Amenity::whereIn('name', $amenitiesToAttach)->pluck('id')->toArray();
        $property->amenities()->syncWithoutDetaching($amenityIds);
      }

      $recordedAt = $property->created_at ?? now();
      DB::table('property_price_histories')->updateOrInsert(
        ['property_id' => $property->id, 'recorded_at' => $recordedAt],
        [
          'price'      => $property->sale_price,
          'currency'   => $property->currency,
          'notes'      => 'Initial price record',
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );

      shuffle($uploadIds);
      foreach ($uploadIds as $order => $uploadId) {
        DB::table('property_images')->updateOrInsert(
          [
            'property_id' => $property->id,
            'image_id'    => $uploadId,
          ],
          [
            'ordering'   => $order,
            'caption'    => null,
            'created_at' => now(),
            'updated_at' => now(),
          ]
        );
      }

      if ($property->title['en'] === 'Cozy Studio in Rabat Medina') {
        $agent = Agent::where('licence_number', 'LIC-003')->first();
        $client = Client::whereHas('user', fn($q) => $q->where('email', 'client@example.com'))->first();

        if ($agent && $client) {
          // Create a 3-month monthly rental starting today
          $monthlyStart = Carbon::today()->startOfDay();
          $monthlyEnd = (clone $monthlyStart)->addMonths(3)->startOfDay();
          $numberOfMonths = 3;

          PropertyRental::create([
            'property_id' => $property->id,
            'agent_id'    => $agent->id,
            'client_id'   => $client->id,
            'start_date'  => $monthlyStart,
            'end_date'    => $monthlyEnd,
            'price'       => $property->monthly_price * $numberOfMonths,
            'type'        => RENTAL_TYPE::MONTHLY->value,
          ]);

          // Create a past 2-month monthly rental (2 months ago)
          $pastMonthlyStart = Carbon::today()->subMonths(2)->startOfDay();
          $pastMonthlyEnd = (clone $pastMonthlyStart)->addMonths(2)->startOfDay();
          $pastNumberOfMonths = 2;

          PropertyRental::create([
            'property_id' => $property->id,
            'agent_id'    => $agent->id,
            'client_id'   => $client->id,
            'start_date'  => $pastMonthlyStart,
            'end_date'    => $pastMonthlyEnd,
            'price'       => $property->monthly_price * $pastNumberOfMonths,
            'type'        => RENTAL_TYPE::MONTHLY->value,
          ]);
        }
      }
    }
  }
}
