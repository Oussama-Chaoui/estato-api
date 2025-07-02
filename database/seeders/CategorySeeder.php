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
        'name'        => 'Moroccan Market Overview',
        'slug'        => 'moroccan-market-overview',
        'description' => 'Comprehensive analysis of Moroccan real estate trends and pricing.',
      ],
      [
        'name'        => 'Buying Guide',
        'slug'        => 'buying-guide',
        'description' => 'Step-by-step advice for purchasing houses, apartments, and land in Morocco.',
      ],
      [
        'name'        => 'Renting Guide',
        'slug'        => 'renting-guide',
        'description' => 'Insider tips on finding and leasing residential or commercial properties.',
      ],
      [
        'name'        => 'Financing & Mortgages',
        'slug'        => 'financing-mortgages',
        'description' => 'Everything you need to know about mortgage rates, loan options, and financing strategies.',
      ],
      [
        'name'        => 'Property Safety & Maintenance',
        'slug'        => 'property-safety-maintenance',
        'description' => 'Advice on home security systems, insurance policies, and regular upkeep tips.',
      ],
      [
        'name'        => 'Neighborhood Spotlights',
        'slug'        => 'neighborhood-spotlights',
        'description' => 'In-depth profiles of Casablanca, Rabat, Marrakech, and other Moroccan neighborhoods.',
      ],
      [
        'name'        => 'Investment Opportunities',
        'slug'        => 'investment-opportunities',
        'description' => 'Guidance on rental yields, flipping properties, and high-return investments.',
      ],
      [
        'name'        => 'Home Improvement & Design',
        'slug'        => 'home-improvement-design',
        'description' => 'Renovation ideas, DIY projects, and interior design trends for Moroccan homes.',
      ],
      [
        'name'        => 'Luxury Real Estate',
        'slug'        => 'luxury-real-estate',
        'description' => 'Spotlight on high-end villas, mansions, and waterfront properties.',
      ],
      [
        'name'        => 'Affordable Housing',
        'slug'        => 'affordable-housing',
        'description' => 'Tips on finding budget-friendly apartments and shared accommodations.',
      ],
      [
        'name'        => 'Commercial Properties',
        'slug'        => 'commercial-properties',
        'description' => 'Advice for investors and business owners on retail spaces, offices, and warehouses.',
      ],
      [
        'name'        => 'Vacation Rentals',
        'slug'        => 'vacation-rentals',
        'description' => 'How to list, manage, and profit from short-term rentals in tourist hotspots.',
      ],
      [
        'name'        => 'Off-Plan Developments',
        'slug'        => 'off-plan-developments',
        'description' => 'Insider information on upcoming projects, pre-sales, and developer reputations.',
      ],
      [
        'name'        => 'Legal & Taxes',
        'slug'        => 'legal-taxes',
        'description' => 'Everything about property law, taxes, and notary fees in Morocco.',
      ],
      [
        'name'        => 'Homeownership Tips',
        'slug'        => 'homeownership-tips',
        'description' => 'Advice for first-time buyers on managing mortgages, down payments, and refinancing.',
      ],
      [
        'name'        => 'Sustainable Building',
        'slug'        => 'sustainable-building',
        'description' => 'Green construction methods, energy-efficient materials, and solar solutions.',
      ],
      [
        'name'        => 'Architecture Trends',
        'slug'        => 'architecture-trends',
        'description' => 'Modern Moroccan architectural styles, renovation aesthetics, and design inspiration.',
      ],
      [
        'name'        => 'Interior Decoration',
        'slug'        => 'interior-decoration',
        'description' => 'Tips on Moroccan-inspired décor, furniture selection, and space optimization.',
      ],
      [
        'name'        => 'Relocation Advice',
        'slug'        => 'relocation-advice',
        'description' => 'Guidance for families and expats moving to Morocco: paperwork, neighborhoods, and culture.',
      ],
      [
        'name'        => 'Market Predictions',
        'slug'        => 'market-predictions',
        'description' => 'Expert forecasts on housing prices, rental demand, and economic indicators.',
      ],
    ]);
  }
}
