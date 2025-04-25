<?php

namespace Database\Seeders;

use App\Enums\LANGUAGE as LANGUAGE_ENUM;
use App\Models\Agent;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $agents =
      [
        [
          'licence_number' => 'LIC-001',
          'experience'     => 5,
          'bio'            => 'Experienced agent with a strong track record.',
          'photo_id'       => null,
          'agency_name'    => 'Acme Realty',
          'agency_address' => '123 Rue de la Paix, Marrakech, Morocco',
          'user_id'        => 3,
        ],
        [
          'licence_number' => 'LIC-002',
          'experience'     => 4,
          'bio'            => 'Dynamic agent known for innovative solutions.',
          'photo_id'       => null,
          'agency_name'    => 'Innovative Realty',
          'agency_address' => '456 Avenue Hassan II, Casablanca, Morocco',
          'user_id'        => 4,
        ],
        [
          'licence_number' => 'LIC-003',
          'experience'     => 8,
          'bio'            => 'Expert agent with a deep understanding of the local market.',
          'photo_id'       => null,
          'agency_name'    => 'Expert Realty',
          'agency_address' => '789 Boulevard Mohamed V, Rabat, Morocco',
          'user_id'        => 5,
        ],
      ];

    $arabicLanguage = Language::createOrFirst([
      'name' => LANGUAGE_ENUM::ARABIC->value,
    ]);

    foreach ($agents as $agent) {
      $agent = Agent::createOrFirst([
        'licence_number' => $agent['licence_number']
      ], $agent);


      $agent->languages()->syncWithoutDetaching([$arabicLanguage->id]);
    };
  }
}
