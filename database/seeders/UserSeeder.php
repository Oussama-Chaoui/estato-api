<?php

namespace Database\Seeders;

use App\Enums\ROLE;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    if (env('APP_ENV') === 'prod') {
      $admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
          'name'     => 'Oussama',
          'password' => bcrypt('fnFPB3TzGWTBoLA'),
          'phone'    => '0612345678',
        ]
      );
      $admin->assignRole(ROLE::ADMIN);

      $client = User::firstOrCreate(
        ['email' => 'client@example.com'],
        [
          'name'     => 'Taha',
          'password' => bcrypt('nRapnRYRdxcE'),
          'phone'    => '0623456789',
        ]
      );
      $client->assignRole(ROLE::CLIENT);

      Client::firstOrCreate(
        ['user_id' => $client->id],
        [
          'nic_number' => 'NIC-1234567',
          'passport'   => null,
          'image_id' => null,
        ]
      );


      $agent = User::firstOrCreate(
        ['email' => 'agent@example.com'],
        [
          'name'     => 'Khalid',
          'password' => bcrypt('agent_prod_secret'),
          'phone'    => '0634567890',
        ]
      );
      $agent->assignRole(ROLE::AGENT);

      $agent2 = User::firstOrCreate(
        ['email' => 'agent2@example.com'],
        [
          'name'     => 'Sami',
          'password' => bcrypt('agent2_prod_secret'),
          'phone'    => '0645678901',
        ]
      );
      $agent2->assignRole(ROLE::AGENT);

      $agent3 = User::firstOrCreate(
        ['email' => 'agent3@example.com'],
        [
          'name'     => 'Amina',
          'password' => bcrypt('agent3_prod_secret'),
          'phone'    => '0656789012',
        ]
      );
      $agent3->assignRole(ROLE::AGENT);
    } else {
      $admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
          'name'     => 'Oussama',
          'password' => bcrypt('admin'),
          'phone'    => '0612345678',
        ]
      );
      $admin->assignRole(ROLE::ADMIN);

      $client = User::firstOrCreate(
        ['email' => 'client@example.com'],
        [
          'name'     => 'Taha',
          'password' => bcrypt('user'),
          'phone'    => '0623456789',
        ]
      );
      $client->assignRole(ROLE::CLIENT);

      Client::firstOrCreate(
        ['user_id' => $client->id],
        [
          'nic_number' => 'NIC-1234567',
          'passport'   => null,
          'image_id' => null,
        ]
      );

      $agent = User::firstOrCreate(
        ['email' => 'agent@example.com'],
        [
          'name'     => 'Khalid',
          'password' => bcrypt('agent'),
          'phone'    => '0634567890',
        ]
      );
      $agent->assignRole(ROLE::AGENT);

      $agent2 = User::firstOrCreate(
        ['email' => 'agent2@example.com'],
        [
          'name'     => 'Sami',
          'password' => bcrypt('agent2'),
          'phone'    => '0645678901',
        ]
      );
      $agent2->assignRole(ROLE::AGENT);

      $agent3 = User::firstOrCreate(
        ['email' => 'agent3@example.com'],
        [
          'name'     => 'Amina',
          'password' => bcrypt('agent3'),
          'phone'    => '0656789012',
        ]
      );
      $agent3->assignRole(ROLE::AGENT);
    }
  }
}
