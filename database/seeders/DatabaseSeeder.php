<?php

namespace Database\Seeders;

use App\Models\Agent;
use Database\Seeders\Permissions\CrudPermissionSeeder;
use Database\Seeders\Permissions\PermissionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(self::seeders());
  }

  public static function seeders()
  {
    $seeders = [
      PermissionSeeder::class,
      CrudPermissionSeeder::class,
      UserSeeder::class,
    ];
    if (!App::environment('prod') && !App::environment('preprod')) {
      $seeders = array_merge($seeders, [
        AgentSeeder::class,
        AmenitySeeder::class,
        LocationSeeder::class,
        PropertySeeder::class,
        CategorySeeder::class,
        TagSeeder::class,
      ]);
    }

    return $seeders;
  }
}
