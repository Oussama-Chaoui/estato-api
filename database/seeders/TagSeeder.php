<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
  public function run()
  {
    Tag::insert([
      [
        'name' => json_encode([
          'en' => 'Casablanca',
          'fr' => 'Casablanca',
          'es' => 'Casablanca',
          'ar' => 'الدار البيضاء'
        ]),
        'slug' => 'casablanca'
      ],
      [
        'name' => json_encode([
          'en' => 'Rabat',
          'fr' => 'Rabat',
          'es' => 'Rabat',
          'ar' => 'الرباط'
        ]),
        'slug' => 'rabat'
      ],
      [
        'name' => json_encode([
          'en' => 'Marrakech',
          'fr' => 'Marrakech',
          'es' => 'Marrakech',
          'ar' => 'مراكش'
        ]),
        'slug' => 'marrakech'
      ],
      [
        'name' => json_encode([
          'en' => 'Tangier',
          'fr' => 'Tanger',
          'es' => 'Tánger',
          'ar' => 'طنجة'
        ]),
        'slug' => 'tangier'
      ],
      [
        'name' => json_encode([
          'en' => 'Agadir',
          'fr' => 'Agadir',
          'es' => 'Agadir',
          'ar' => 'أكادير'
        ]),
        'slug' => 'agadir'
      ],
      [
        'name' => json_encode([
          'en' => 'Fez',
          'fr' => 'Fès',
          'es' => 'Fez',
          'ar' => 'فاس'
        ]),
        'slug' => 'fez'
      ],
      [
        'name' => json_encode([
          'en' => 'Chefchaouen',
          'fr' => 'Chefchaouen',
          'es' => 'Chefchaouen',
          'ar' => 'شفشاون'
        ]),
        'slug' => 'chefchaouen'
      ],
      [
        'name' => json_encode([
          'en' => 'Mortgage Rates',
          'fr' => 'Taux Hypothécaires',
          'es' => 'Tasas Hipotecarias',
          'ar' => 'أسعار الرهن'
        ]),
        'slug' => 'mortgage-rates'
      ],
      [
        'name' => json_encode([
          'en' => 'Property Tax',
          'fr' => 'Taxe Foncière',
          'es' => 'Impuesto Predial',
          'ar' => 'الضرائب العقارية'
        ]),
        'slug' => 'property-tax'
      ],
      [
        'name' => json_encode([
          'en' => 'Legal Advice',
          'fr' => 'Conseil Juridique',
          'es' => 'Asesoría Legal',
          'ar' => 'النصائح القانونية'
        ]),
        'slug' => 'legal-advice'
      ],
      [
        'name' => json_encode([
          'en' => 'Home Staging',
          'fr' => 'Mise en Scène',
          'es' => 'Puesta en Escena',
          'ar' => 'تنسيق المنزل'
        ]),
        'slug' => 'home-staging'
      ],
      [
        'name' => json_encode([
          'en' => 'Interior Design',
          'fr' => 'Design d\'Intérieur',
          'es' => 'Diseño de Interiores',
          'ar' => 'التصميم الداخلي'
        ]),
        'slug' => 'interior-design'
      ],
      [
        'name' => json_encode([
          'en' => 'Energy Efficiency',
          'fr' => 'Efficacité Énergétique',
          'es' => 'Eficiencia Energética',
          'ar' => 'توفير الطاقة'
        ]),
        'slug' => 'energy-efficiency'
      ],
      [
        'name' => json_encode([
          'en' => 'Real Estate Investment',
          'fr' => 'Investissement Immobilier',
          'es' => 'Inversión Inmobiliaria',
          'ar' => 'الاستثمار العقاري'
        ]),
        'slug' => 'real-estate-investment'
      ],
      [
        'name' => json_encode([
          'en' => 'Rental Yield',
          'fr' => 'Rendement Locatif',
          'es' => 'Rendimiento de Alquiler',
          'ar' => 'ربح الإيجار'
        ]),
        'slug' => 'rental-yield'
      ],
      [
        'name' => json_encode([
          'en' => 'First-Time Buyer',
          'fr' => 'Premier Acheteur',
          'es' => 'Comprador Primerizo',
          'ar' => 'المشتري لأول مرة'
        ]),
        'slug' => 'first-time-buyer'
      ],
      [
        'name' => json_encode([
          'en' => 'Luxury Villa',
          'fr' => 'Villa de Luxe',
          'es' => 'Villa de Lujo',
          'ar' => 'فيلا راقية'
        ]),
        'slug' => 'luxury-villa'
      ],
      [
        'name' => json_encode([
          'en' => 'Studio Apartment',
          'fr' => 'Studio',
          'es' => 'Estudio',
          'ar' => 'استوديو'
        ]),
        'slug' => 'studio-apartment'
      ],
      [
        'name' => json_encode([
          'en' => 'Commercial Space',
          'fr' => 'Espace Commercial',
          'es' => 'Espacio Comercial',
          'ar' => 'محل تجاري'
        ]),
        'slug' => 'commercial-space'
      ],
      [
        'name' => json_encode([
          'en' => 'Vacation Home',
          'fr' => 'Résidence de Vacances',
          'es' => 'Casa de Vacaciones',
          'ar' => 'بيت سياحي'
        ]),
        'slug' => 'vacation-home'
      ],
    ]);
  }
}
