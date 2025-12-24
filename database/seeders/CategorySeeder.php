<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
  public function run()
  {
    Category::insert([
      [
        'name' => json_encode([
          'en' => 'Moroccan Market Overview',
          'fr' => 'Aperçu du Marché Marocain',
          'es' => 'Visión General del Mercado Marroquí',
          'ar' => 'السوق العقاري المغربي'
        ]),
        'slug'        => 'moroccan-market-overview',
        'description' => json_encode([
          'en' => 'Comprehensive analysis of Moroccan real estate trends and pricing.',
          'fr' => 'Analyse complète des tendances et prix immobiliers au Maroc.',
          'es' => 'Análisis integral de las tendencias y precios inmobiliarios en Marruecos.',
          'ar' => 'تحليل شامل لسوق العقارات المغربي وأسعاره واتجاهاته.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Buying Guide',
          'fr' => 'Guide d\'Achat',
          'es' => 'Guía de Compra',
          'ar' => 'دليل شراء العقار'
        ]),
        'slug'        => 'buying-guide',
        'description' => json_encode([
          'en' => 'Step-by-step advice for purchasing houses, apartments, and land in Morocco.',
          'fr' => 'Conseils étape par étape pour acheter des maisons, appartements et terrains au Maroc.',
          'es' => 'Consejos paso a paso para comprar casas, apartamentos y terrenos en Marruecos.',
          'ar' => 'إرشادات مفصلة لشراء العقارات والأراضي في المغرب.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Renting Guide',
          'fr' => 'Guide de Location',
          'es' => 'Guía de Alquiler',
          'ar' => 'دليل استئجار العقار'
        ]),
        'slug'        => 'renting-guide',
        'description' => json_encode([
          'en' => 'Insider tips on finding and leasing residential or commercial properties.',
          'fr' => 'Conseils d\'initiés pour trouver et louer des propriétés résidentielles ou commerciales.',
          'es' => 'Consejos internos para encontrar y alquilar propiedades residenciales o comerciales.',
          'ar' => 'نصائح عملية للعثور على العقارات السكنية والتجارية.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Financing & Mortgages',
          'fr' => 'Financement et Hypothèques',
          'es' => 'Financiamiento e Hipotecas',
          'ar' => 'التمويل العقاري'
        ]),
        'slug'        => 'financing-mortgages',
        'description' => json_encode([
          'en' => 'Everything you need to know about mortgage rates, loan options, and financing strategies.',
          'fr' => 'Tout ce que vous devez savoir sur les taux hypothécaires, options de prêt et stratégies de financement.',
          'es' => 'Todo lo que necesitas saber sobre tasas hipotecarias, opciones de préstamo y estrategias de financiamiento.',
          'ar' => 'كل ما تحتاج معرفته عن القروض العقارية والتمويل.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Property Safety & Maintenance',
          'fr' => 'Sécurité et Entretien des Biens',
          'es' => 'Seguridad y Mantenimiento de Propiedades',
          'ar' => 'أمان وصيانة العقار'
        ]),
        'slug'        => 'property-safety-maintenance',
        'description' => json_encode([
          'en' => 'Advice on home security systems, insurance policies, and regular upkeep tips.',
          'fr' => 'Conseils sur les systèmes de sécurité domestique, polices d\'assurance et conseils d\'entretien régulier.',
          'es' => 'Consejos sobre sistemas de seguridad doméstica, pólizas de seguro y consejos de mantenimiento regular.',
          'ar' => 'نصائح حول الأمان والتأمين والصيانة الدورية.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Neighborhood Spotlights',
          'fr' => 'Focus sur les Quartiers',
          'es' => 'Enfoque en los Barrios',
          'ar' => 'الأحياء والمناطق'
        ]),
        'slug'        => 'neighborhood-spotlights',
        'description' => json_encode([
          'en' => 'In-depth profiles of Casablanca, Rabat, Marrakech, and other Moroccan neighborhoods.',
          'fr' => 'Profils détaillés de Casablanca, Rabat, Marrakech et autres quartiers marocains.',
          'es' => 'Perfiles detallados de Casablanca, Rabat, Marrakech y otros barrios marroquíes.',
          'ar' => 'تعريف شامل بأحياء الدار البيضاء والرباط ومراكش وغيرها.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Investment Opportunities',
          'fr' => 'Opportunités d\'Investissement',
          'es' => 'Oportunidades de Inversión',
          'ar' => 'فرص الاستثمار العقاري'
        ]),
        'slug'        => 'investment-opportunities',
        'description' => json_encode([
          'en' => 'Guidance on rental yields, flipping properties, and high-return investments.',
          'fr' => 'Conseils sur les rendements locatifs, le retournement de propriétés et les investissements à haut rendement.',
          'es' => 'Orientación sobre rendimientos de alquiler, renovación de propiedades e inversiones de alto rendimiento.',
          'ar' => 'إرشادات حول الاستثمار العقاري والعوائد المرتفعة.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Home Improvement & Design',
          'fr' => 'Amélioration et Design de Maison',
          'es' => 'Mejoras y Diseño del Hogar',
          'ar' => 'تحسين وتصميم المنزل'
        ]),
        'slug'        => 'home-improvement-design',
        'description' => json_encode([
          'en' => 'Renovation ideas, DIY projects, and interior design trends for Moroccan homes.',
          'fr' => 'Idées de rénovation, projets DIY et tendances de design d\'intérieur pour les maisons marocaines.',
          'es' => 'Ideas de renovación, proyectos DIY y tendencias de diseño de interiores para hogares marroquíes.',
          'ar' => 'أفكار التجديد والتصميم الداخلي للمنازل المغربية.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Luxury Real Estate',
          'fr' => 'Immobilier de Luxe',
          'es' => 'Bienes Raíces de Lujo',
          'ar' => 'العقارات الراقية'
        ]),
        'slug'        => 'luxury-real-estate',
        'description' => json_encode([
          'en' => 'Spotlight on high-end villas, mansions, and waterfront properties.',
          'fr' => 'Focus sur les villas haut de gamme, manoirs et propriétés en bord de mer.',
          'es' => 'Enfoque en villas de lujo, mansiones y propiedades frente al mar.',
          'ar' => 'عرض للفيلات والقصور والعقارات المطلة على البحر.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Affordable Housing',
          'fr' => 'Logement Abordable',
          'es' => 'Vivienda Asequible',
          'ar' => 'الإسكان الاقتصادي'
        ]),
        'slug'        => 'affordable-housing',
        'description' => json_encode([
          'en' => 'Tips on finding budget-friendly apartments and shared accommodations.',
          'fr' => 'Conseils pour trouver des appartements abordables et des logements partagés.',
          'es' => 'Consejos para encontrar apartamentos económicos y alojamientos compartidos.',
          'ar' => 'نصائح للعثور على شقق اقتصادية وسكن مناسب.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Commercial Properties',
          'fr' => 'Propriétés Commerciales',
          'es' => 'Propiedades Comerciales',
          'ar' => 'العقارات التجارية'
        ]),
        'slug'        => 'commercial-properties',
        'description' => json_encode([
          'en' => 'Advice for investors and business owners on retail spaces, offices, and warehouses.',
          'fr' => 'Conseils pour les investisseurs et propriétaires d\'entreprise sur les espaces de vente, bureaux et entrepôts.',
          'es' => 'Consejos para inversores y propietarios de negocios sobre espacios comerciales, oficinas y almacenes.',
          'ar' => 'نصائح للمستثمرين حول العقارات التجارية والمكاتب.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Vacation Rentals',
          'fr' => 'Locations de Vacances',
          'es' => 'Alquileres Vacacionales',
          'ar' => 'إيجارات السياحة'
        ]),
        'slug'        => 'vacation-rentals',
        'description' => json_encode([
          'en' => 'How to list, manage, and profit from short-term rentals in tourist hotspots.',
          'fr' => 'Comment lister, gérer et profiter des locations de courte durée dans les destinations touristiques.',
          'es' => 'Cómo listar, gestionar y obtener ganancias de alquileres de corta duración en destinos turísticos.',
          'ar' => 'كيفية إدارة وإيجار العقارات للسياح.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Off-Plan Developments',
          'fr' => 'Développements sur Plan',
          'es' => 'Desarrollos sobre Planos',
          'ar' => 'المشاريع الجديدة'
        ]),
        'slug'        => 'off-plan-developments',
        'description' => json_encode([
          'en' => 'Insider information on upcoming projects, pre-sales, and developer reputations.',
          'fr' => 'Informations d\'initiés sur les projets à venir, préventes et réputations des promoteurs.',
          'es' => 'Información interna sobre proyectos próximos, preventas y reputaciones de desarrolladores.',
          'ar' => 'معلومات عن المشاريع الجديدة والبيع المسبق.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Legal & Taxes',
          'fr' => 'Légal et Taxes',
          'es' => 'Legal e Impuestos',
          'ar' => 'القانون والضرائب'
        ]),
        'slug'        => 'legal-taxes',
        'description' => json_encode([
          'en' => 'Everything about property law, taxes, and notary fees in Morocco.',
          'fr' => 'Tout sur le droit immobilier, les taxes et les frais de notaire au Maroc.',
          'es' => 'Todo sobre la ley de propiedad, impuestos y honorarios de notario en Marruecos.',
          'ar' => 'كل ما تحتاج معرفته عن القانون والضرائب العقارية.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Homeownership Tips',
          'fr' => 'Conseils de Propriétaire',
          'es' => 'Consejos de Propietario',
          'ar' => 'نصائح للملاك'
        ]),
        'slug'        => 'homeownership-tips',
        'description' => json_encode([
          'en' => 'Advice for first-time buyers on managing mortgages, down payments, and refinancing.',
          'fr' => 'Conseils pour les acheteurs pour la première fois sur la gestion des hypothèques, acomptes et refinancement.',
          'es' => 'Consejos para compradores primerizos sobre gestión de hipotecas, pagos iniciales y refinanciamiento.',
          'ar' => 'نصائح للمشترين الجدد حول القروض والتمويل.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Sustainable Building',
          'fr' => 'Construction Durable',
          'es' => 'Construcción Sostenible',
          'ar' => 'البناء الأخضر'
        ]),
        'slug'        => 'sustainable-building',
        'description' => json_encode([
          'en' => 'Green construction methods, energy-efficient materials, and solar solutions.',
          'fr' => 'Méthodes de construction écologiques, matériaux écoénergétiques et solutions solaires.',
          'es' => 'Métodos de construcción ecológica, materiales energéticamente eficientes y soluciones solares.',
          'ar' => 'طرق البناء الصديق للبيئة والطاقة الشمسية.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Architecture Trends',
          'fr' => 'Tendances Architecturales',
          'es' => 'Tendencias Arquitectónicas',
          'ar' => 'اتجاهات البناء'
        ]),
        'slug'        => 'architecture-trends',
        'description' => json_encode([
          'en' => 'Modern Moroccan architectural styles, renovation aesthetics, and design inspiration.',
          'fr' => 'Styles architecturaux marocains modernes, esthétique de rénovation et inspiration de design.',
          'es' => 'Estilos arquitectónicos marroquíes modernos, estética de renovación e inspiración de diseño.',
          'ar' => 'الأنماط المعمارية الحديثة وأفكار التجديد.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Interior Decoration',
          'fr' => 'Décoration d\'Intérieur',
          'es' => 'Decoración de Interiores',
          'ar' => 'التصميم الداخلي'
        ]),
        'slug'        => 'interior-decoration',
        'description' => json_encode([
          'en' => 'Tips on Moroccan-inspired décor, furniture selection, and space optimization.',
          'fr' => 'Conseils sur la décoration d\'inspiration marocaine, la sélection de meubles et l\'optimisation de l\'espace.',
          'es' => 'Consejos sobre decoración de inspiración marroquí, selección de muebles y optimización del espacio.',
          'ar' => 'نصائح حول التصميم الداخلي والأثاث المغربي.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Relocation Advice',
          'fr' => 'Conseils de Déménagement',
          'es' => 'Consejos de Reubicación',
          'ar' => 'نصائح السكن'
        ]),
        'slug'        => 'relocation-advice',
        'description' => json_encode([
          'en' => 'Guidance for families and expats moving to Morocco: paperwork, neighborhoods, and culture.',
          'fr' => 'Conseils pour les familles et expatriés qui déménagent au Maroc : paperasse, quartiers et culture.',
          'es' => 'Orientación para familias y expatriados que se mudan a Marruecos: trámites, barrios y cultura.',
          'ar' => 'إرشادات للعائلات والمغتربين حول السكن في المغرب.'
        ]),
      ],
      [
        'name' => json_encode([
          'en' => 'Market Predictions',
          'fr' => 'Prédictions du Marché',
          'es' => 'Predicciones del Mercado',
          'ar' => 'توقعات السوق العقاري'
        ]),
        'slug'        => 'market-predictions',
        'description' => json_encode([
          'en' => 'Expert forecasts on housing prices, rental demand, and economic indicators.',
          'fr' => 'Prévisions d\'experts sur les prix du logement, la demande de location et les indicateurs économiques.',
          'es' => 'Pronósticos de expertos sobre precios de vivienda, demanda de alquiler e indicadores económicos.',
          'ar' => 'توقعات الخبراء حول أسعار العقارات والطلب.'
        ]),
      ],
    ]);
  }
}
