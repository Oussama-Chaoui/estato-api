<?php

namespace Database\Seeders;

use App\Enums\LANGUAGE as LANGUAGE_ENUM;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    foreach (LANGUAGE_ENUM::cases() as $languageEnum) {
      Language::firstOrCreate(
        ['name' => $languageEnum->value],
        [
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );
    }
  }
}

