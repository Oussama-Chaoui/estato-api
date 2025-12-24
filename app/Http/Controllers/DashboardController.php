<?php

namespace App\Http\Controllers;

use App\Enums\APPOINTMENT_STATUS;
use App\Enums\INQUIRY_STATUS;
use App\Enums\PROPERTY_STATUS;
use App\Enums\ROLE;
use App\Models\Agent;
use App\Models\AgentAppliance;
use App\Models\Appointment;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyRental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function getStatistics(Request $request)
  {
    try {
      $user = $request->user();

      if (!$user->hasPermission('dashboard', 'read')) {
        return response()->json([
          'success' => false,
          'errors' => [__('common.permission_denied')],
        ]);
      }

      $now = Carbon::now();
      $startOfMonth = $now->copy()->startOfMonth();
      $startOfYear = $now->copy()->startOfYear();
      $lastMonth = $now->copy()->subMonth();
      $lastMonthStart = $lastMonth->copy()->startOfMonth();
      $lastMonthEnd = $lastMonth->copy()->endOfMonth();

      // Current Month Statistics
      $currentMonthProperties = Property::whereBetween('created_at', [$startOfMonth, $now])->count();
      $currentMonthUsers = User::whereBetween('created_at', [$startOfMonth, $now])->count();
      $currentMonthAgents = Agent::whereBetween('created_at', [$startOfMonth, $now])->count();
      $currentMonthInquiries = Inquiry::whereBetween('created_at', [$startOfMonth, $now])->count();

      // Current Month Clients (users with CLIENT role)
      $currentMonthClients = User::whereHas('roles', function ($query) {
        $query->where('name', ROLE::CLIENT->value);
      })->whereBetween('created_at', [$startOfMonth, $now])->count();

      $currentMonthAppointments = Appointment::whereBetween('created_at', [$startOfMonth, $now])->count();
      $currentMonthRentals = PropertyRental::whereBetween('created_at', [$startOfMonth, $now])->count();
      $currentMonthRevenue = PropertyRental::whereBetween('start_date', [$startOfMonth, $now])->sum('price');

      // Last Month Statistics
      $lastMonthProperties = Property::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthUsers = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthAgents = Agent::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthInquiries = Inquiry::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthAppointments = Appointment::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthRentals = PropertyRental::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $lastMonthRevenue = PropertyRental::whereBetween('start_date', [$lastMonthStart, $lastMonthEnd])->sum('price');

      // Last Month Clients (users with CLIENT role)
      $lastMonthClients = User::whereHas('roles', function ($query) {
        $query->where('name', ROLE::CLIENT->value);
      })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();


      // Calculate Growth Percentages
      $propertiesGrowth = $this->calculateGrowth($currentMonthProperties, $lastMonthProperties);
      $usersGrowth = $this->calculateGrowth($currentMonthUsers, $lastMonthUsers);

      // Custom agent growth calculation (handles 0 to positive better)
      $agentsGrowth = $lastMonthAgents > 0 
        ? (($currentMonthAgents - $lastMonthAgents) / $lastMonthAgents) * 100
        : ($currentMonthAgents > 0 ? ($currentMonthAgents * 100) : 0);
        
      // Custom revenue growth calculation (handles 0 to positive better)
      $revenueGrowth = $lastMonthRevenue > 0 
        ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
        : ($currentMonthRevenue > 0 ? ($currentMonthRevenue * 100) : 0);

      $clientsGrowth = $this->calculateGrowth($currentMonthClients, $lastMonthClients);
      // Client growth breakdown for tooltip
      $clientGrowthBreakdown = [
        'current_month_clients' => $currentMonthClients,
        'last_month_clients' => $lastMonthClients,
        'clients_growth' => $clientsGrowth,
        'total_clients' => User::whereHas('roles', function ($query) {
          $query->where('name', ROLE::CLIENT->value);
        })->count(),
        'current_month_start' => $startOfMonth->format('Y-m-d'),
        'current_month_end' => $now->format('Y-m-d'),
        'last_month_start' => $lastMonthStart->format('Y-m-d'),
        'last_month_end' => $lastMonthEnd->format('Y-m-d')
      ];

      $inquiriesGrowth = $this->calculateGrowth($currentMonthInquiries, $lastMonthInquiries);
      $appointmentsGrowth = $this->calculateGrowth($currentMonthAppointments, $lastMonthAppointments);
      $rentalsGrowth = $this->calculateGrowth($currentMonthRentals, $lastMonthRentals);
      
      // KPI Calculation: 60% of total monthly rental value of available properties
      $availableProperties = Property::where('status', '!=', PROPERTY_STATUS::SOLD->value)
        ->where('daily_price_enabled', true)
        ->get();
      
      $totalDailyRentalValue = $availableProperties->sum('daily_price');
      $totalMonthlyRentalValue = $totalDailyRentalValue * 30; // Daily * 30 = Monthly potential
      $kpiTarget = $totalMonthlyRentalValue * 0.6; // 60% of total monthly rental value
      $revenueKpiProgress = $kpiTarget > 0 ? min(($currentMonthRevenue / $kpiTarget) * 100, 100) : 0;
      
      // Agent Activity Rate: Agents with rentals vs total agents
      $totalAgents = Agent::count();

      // Current month activity rate (agents with rentals this month)
      $activeAgentsThisMonth = Agent::whereHas('rentals', function ($query) use ($startOfMonth, $now) {
        $query->whereBetween('created_at', [$startOfMonth, $now]);
      })->count();
      $agentActivityRate = $totalAgents > 0 ? ($activeAgentsThisMonth / $totalAgents) * 100 : 0;

      // Last month activity rate (agents with rentals last month)
      $activeAgentsLastMonth = Agent::whereHas('rentals', function ($query) use ($lastMonthStart, $lastMonthEnd) {
        $query->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]);
      })->count();
      $lastMonthAgentActivityRate = $totalAgents > 0 ? ($activeAgentsLastMonth / $totalAgents) * 100 : 0;

      // Calculate activity rate growth
      $agentActivityGrowth = $this->calculateGrowth($agentActivityRate, $lastMonthAgentActivityRate);

      // Agent activity growth breakdown for tooltip
      $agentActivityBreakdown = [
        'current_month_activity_rate' => $agentActivityRate,
        'last_month_activity_rate' => $lastMonthAgentActivityRate,
        'activity_growth' => $agentActivityGrowth,
        'active_agents_this_month' => $activeAgentsThisMonth,
        'active_agents_last_month' => $activeAgentsLastMonth,
        'total_agents' => $totalAgents,
        'current_month_start' => $startOfMonth->format('Y-m-d'),
        'current_month_end' => $now->format('Y-m-d'),
        'last_month_start' => $lastMonthStart->format('Y-m-d'),
        'last_month_end' => $lastMonthEnd->format('Y-m-d')
      ];

      // Total Statistics
      $totalProperties = Property::count();
      $totalUsers = User::count();
      $totalAgents = Agent::count();
      $totalInquiries = Inquiry::count();
      $totalAppointments = Appointment::count();
      $totalRentals = PropertyRental::count();

      // Property Status Distribution
      $propertyStatusStats = Property::select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get()
        ->keyBy('status');

      // Recent Activity
      $recentInquiries = Inquiry::with(['property', 'user'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

      $recentAppointments = Appointment::with(['property', 'user', 'agent'])
        ->orderBy('scheduled_at', 'desc')
        ->limit(5)
        ->get();

      $recentRentals = PropertyRental::with(['property', 'renter', 'agent'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();


      $recentAgentApplications = AgentAppliance::orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

      // Top Performing Agents (by rental count)
      $topAgents = Agent::with(['user', 'rentals'])
        ->withCount('rentals')
        ->orderBy('rentals_count', 'desc')
        ->limit(5)
        ->get();

      // Monthly Trends (last 6 months)
      $monthlyTrends = [];
      for ($i = 5; $i >= 0; $i--) {
        $month = $now->copy()->subMonths($i);
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        // Calculate sales revenue for properties sold in this month
        $salesRevenue = Property::where('status', PROPERTY_STATUS::SOLD->value)
          ->whereBetween('sold_at', [$monthStart, $monthEnd])
          ->sum('sale_price');

        // Rental revenue (already tracked)
        $rentalRevenue = PropertyRental::whereBetween('start_date', [$monthStart, $monthEnd])->sum('price');

        // Total revenue
        $totalRevenue = $salesRevenue + $rentalRevenue;

        $monthlyTrends[] = [
          'month' => $month->format('M Y'),
          'properties' => Property::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
          'rentals' => PropertyRental::whereBetween('start_date', [$monthStart, $monthEnd])->count(),
          'revenue' => $totalRevenue,
          'sales_revenue' => $salesRevenue,
          'rental_revenue' => $rentalRevenue,
          'inquiries' => Inquiry::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
        ];
      }

      // Property Type Distribution
      $propertyTypeStats = Property::select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->get();

      // Inquiry Status Distribution
      $inquiryStatusStats = Inquiry::select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get()
        ->keyBy('status');

      // Appointment Status Distribution
      $appointmentStatusStats = Appointment::select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get()
        ->keyBy('status');

      // Occupancy Rate (properties that are rented vs total)
      $rentedProperties = Property::where('status', PROPERTY_STATUS::RENTED)->count();
      $occupancyRate = $totalProperties > 0 ? ($rentedProperties / $totalProperties) * 100 : 0;

      // Property Utilization Rate (actual rental days vs available rental days this month)
      $availableProperties = Property::where('status', '!=', PROPERTY_STATUS::SOLD->value)
        ->where(function ($query) {
          $query->where('daily_price_enabled', true)
            ->orWhere('monthly_price_enabled', true);
        })
        ->count();

      // Calculate total available rental days for current month (NOT full month, but days from start to now)
      $daysFromStartToNow = $startOfMonth->diffInDays($now->startOfDay()) + 1; // Use startOfDay() to get whole days only
      $totalAvailableRentalDays = $availableProperties * $daysFromStartToNow;


      // Calculate actual rented days in current month - FIXED QUERY
      $currentMonthRentals = PropertyRental::where(function ($query) use ($startOfMonth, $now) {
        $query->where(function ($q) use ($startOfMonth, $now) {
          // Rentals that start within current period
          $q->whereBetween('start_date', [$startOfMonth, $now]);
        })->orWhere(function ($q) use ($startOfMonth, $now) {
          // Rentals that end within current period  
          $q->whereBetween('end_date', [$startOfMonth, $now]);
        })->orWhere(function ($q) use ($startOfMonth, $now) {
          // Rentals that span the entire current period
          $q->where('start_date', '<=', $startOfMonth)
            ->where('end_date', '>=', $now);
        });
      })->get();

      $actualRentedDays = 0;

      foreach ($currentMonthRentals as $rental) {
        // Calculate overlap with current month (from start of month to now) - FIXED TO USE WHOLE DAYS
        $rentalStart = max($rental->start_date->startOfDay(), $startOfMonth->startOfDay());
        $rentalEnd = min($rental->end_date->startOfDay(), $now->startOfDay());

        if ($rentalStart <= $rentalEnd) {
          // Calculate days between start and end (inclusive) - WHOLE DAYS ONLY
          $daysInOverlap = $rentalStart->diffInDays($rentalEnd) + 1;
          $actualRentedDays += $daysInOverlap;
        }
      }


      $propertyUtilizationRate = $totalAvailableRentalDays > 0
        ? min(($actualRentedDays / $totalAvailableRentalDays) * 100, 100)
        : 0;

      // Calculate last month's utilization rate for comparison
      $lastMonthUtilizationRate = $this->calculateLastMonthUtilizationRate($lastMonthStart, $lastMonthEnd);

      // Property utilization breakdown for tooltip
      $propertyUtilizationBreakdown = [
        'available_properties' => $availableProperties,
        'days_in_month' => $daysFromStartToNow, // Fixed: use actual days from start to now, not full month
        'total_available_days' => $totalAvailableRentalDays,
        'actual_rented_days' => $actualRentedDays,
        'utilization_rate' => $propertyUtilizationRate,
        'last_month_utilization_rate' => $lastMonthUtilizationRate,
        'current_date' => $now->format('Y-m-d'),
        'month_start' => $startOfMonth->format('Y-m-d'),
        'rental_count' => $currentMonthRentals->count()
      ];


      // Average Rental Price
      $averageRentalPrice = PropertyRental::avg('price');

      // Property Portfolio Health Metrics
      $forSaleProperties = Property::where('status', PROPERTY_STATUS::FOR_SALE->value)->count();
      $forRentProperties = Property::where('status', PROPERTY_STATUS::FOR_RENT->value)->count();
      $soldProperties = Property::where('status', PROPERTY_STATUS::SOLD->value)->count();
      $rentedProperties = Property::where('status', PROPERTY_STATUS::RENTED->value)->count();
      $featuredProperties = Property::where('featured', true)->count();
      $vrProperties = Property::where('has_vr', true)->count();

      // Property Type Distribution
      $propertyTypeDistribution = Property::select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->get();

      // Agent Applications Metrics
      $totalAgentApplications = AgentAppliance::count();
      $pendingAgentApplications = AgentAppliance::where('status', 'PENDING')->count();
      $approvedAgentApplications = AgentAppliance::where('status', 'APPROVED')->count();
      $rejectedAgentApplications = AgentAppliance::where('status', 'REJECTED')->count();

      // Blog Posts Metrics
      $totalPosts = Post::count();
      $publishedPosts = Post::where('status', 'PUBLISHED')->count();
      $draftPosts = Post::where('status', 'DRAFT')->count();
      $archivedPosts = Post::where('status', 'ARCHIVED')->count();

      // Last Month Metrics for Growth Calculations
      $lastMonthFeaturedProperties = Property::where('featured', true)
        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->count();
      $lastMonthVrProperties = Property::where('has_vr', true)
        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->count();
      $lastMonthPublishedPosts = Post::where('status', 'PUBLISHED')
        ->whereBetween('published_at', [$lastMonthStart, $lastMonthEnd])
        ->count();

      // Calculate Growth for New Metrics (using same logic as revenue growth)
      $featuredPropertiesGrowth = $lastMonthFeaturedProperties > 0 
        ? (($featuredProperties - $lastMonthFeaturedProperties) / $lastMonthFeaturedProperties) * 100
        : ($featuredProperties > 0 ? ($featuredProperties * 100) : 0);
        
      $vrPropertiesGrowth = $lastMonthVrProperties > 0 
        ? (($vrProperties - $lastMonthVrProperties) / $lastMonthVrProperties) * 100
        : ($vrProperties > 0 ? ($vrProperties * 100) : 0);
        
      $publishedPostsGrowth = $lastMonthPublishedPosts > 0 
        ? (($publishedPosts - $lastMonthPublishedPosts) / $lastMonthPublishedPosts) * 100
        : ($publishedPosts > 0 ? ($publishedPosts * 100) : 0);
      
      // Agent Applications Growth (comparing current month new applications)
      $currentMonthAgentApplications = AgentAppliance::whereBetween('created_at', [$startOfMonth, $now])->count();
      $lastMonthAgentApplications = AgentAppliance::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
      $agentApplicationsGrowth = $lastMonthAgentApplications > 0 
        ? (($currentMonthAgentApplications - $lastMonthAgentApplications) / $lastMonthAgentApplications) * 100
        : ($currentMonthAgentApplications > 0 ? ($currentMonthAgentApplications * 100) : 0);

      // Property Features Summary
      $totalBedrooms = Property::join('property_features', 'properties.id', '=', 'property_features.property_id')
        ->sum('property_features.bedrooms');
      $totalBathrooms = Property::join('property_features', 'properties.id', '=', 'property_features.property_id')
        ->sum('property_features.bathrooms');
      $totalArea = Property::join('property_features', 'properties.id', '=', 'property_features.property_id')
        ->sum('property_features.area');

      // Properties by Location (top 5 cities)
      $topLocations = Property::with('location.city')
        ->select('location_id', DB::raw('count(*) as count'))
        ->groupBy('location_id')
        ->orderBy('count', 'desc')
        ->limit(5)
        ->get();

      // Fix: Get the actual location and city data for the grouped results
      $topLocationsWithData = [];
      foreach ($topLocations as $location) {
        $locationModel = \App\Models\Location::with('city')->find($location->location_id);
        if ($locationModel && $locationModel->city) {
          $topLocationsWithData[] = [
            'location_id' => $location->location_id,
            'count' => $location->count,
            'location' => [
              'id' => $locationModel->id,
              'city' => [
                'id' => $locationModel->city->id,
                'names' => $locationModel->city->names
              ]
            ]
          ];
        }
      }

      return response()->json([
        'success' => true,
        'data' => [
          'statistics' => [
            'total_properties' => $totalProperties,
            'total_users' => $totalUsers,
            'total_agents' => $totalAgents,
            'total_inquiries' => $totalInquiries,
            'total_appointments' => $totalAppointments,
            'total_rentals' => $totalRentals,
            'monthly_revenue' => $currentMonthRevenue,
            'revenue_growth' => round($revenueGrowth, 2),
            'occupancy_rate' => round($occupancyRate, 2),
            'property_utilization_rate' => round($propertyUtilizationRate, 2),
            'property_utilization_breakdown' => $propertyUtilizationBreakdown,
            'client_growth_breakdown' => $clientGrowthBreakdown,
            'agent_activity_breakdown' => $agentActivityBreakdown,
            'agent_activity_rate' => round($agentActivityRate, 2),
            'revenue_kpi_progress' => round($revenueKpiProgress, 2),
            'average_rental_price' => round($averageRentalPrice, 2),
            // Property Portfolio Health
            'for_sale_properties' => $forSaleProperties,
            'for_rent_properties' => $forRentProperties,
            'sold_properties' => $soldProperties,
            'rented_properties' => $rentedProperties,
            'featured_properties' => $featuredProperties,
            'vr_properties' => $vrProperties,
            // Agent Applications
            'total_agent_applications' => $totalAgentApplications,
            'pending_agent_applications' => $pendingAgentApplications,
            'approved_agent_applications' => $approvedAgentApplications,
            'rejected_agent_applications' => $rejectedAgentApplications,
            // Blog Content
            'total_posts' => $totalPosts,
            'published_posts' => $publishedPosts,
            'draft_posts' => $draftPosts,
            'archived_posts' => $archivedPosts,
            // Property Features
            'total_bedrooms' => $totalBedrooms,
            'total_bathrooms' => $totalBathrooms,
            'total_area' => $totalArea,
            // New Growth percentages
            'featured_properties_growth' => round($featuredPropertiesGrowth, 2),
            'vr_properties_growth' => round($vrPropertiesGrowth, 2),
            'published_posts_growth' => round($publishedPostsGrowth, 2),
            'agent_applications_growth' => round($agentApplicationsGrowth, 2),
            // Growth percentages
            'properties_growth' => round($propertiesGrowth, 2),
            'users_growth' => round($usersGrowth, 2),
            'agents_growth' => round($agentsGrowth, 2),
            'agent_activity_growth' => round($agentActivityGrowth, 2),
            'clients_growth' => round($clientsGrowth, 2),
            'inquiries_growth' => round($inquiriesGrowth, 2),
            'appointments_growth' => round($appointmentsGrowth, 2),
            'rentals_growth' => round($rentalsGrowth, 2),
          ],
          'property_status_stats' => $propertyStatusStats,
          'property_type_stats' => $propertyTypeDistribution,
          'inquiry_status_stats' => $inquiryStatusStats,
          'appointment_status_stats' => $appointmentStatusStats,
          'monthly_trends' => $monthlyTrends,
          'top_agents' => $topAgents,
          'top_locations' => $topLocationsWithData,
          'recent_inquiries' => $recentInquiries,
          'recent_appointments' => $recentAppointments,
          'recent_rentals' => $recentRentals,
          'recent_agent_applications' => $recentAgentApplications,
        ],
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'errors' => [__('common.unexpected_error')],
      ]);
    }
  }

  private function calculateGrowth($current, $previous)
  {
    if ($previous == 0) {
      return $current > 0 ? 100 : 0;
    }
    return (($current - $previous) / $previous) * 100;
  }

  private function calculateLastMonthUtilizationRate($lastMonthStart, $lastMonthEnd)
  {
    // Get properties available for rent last month (same logic as current month)
    $availableProperties = Property::where('status', '!=', PROPERTY_STATUS::SOLD->value)
      ->where(function ($query) {
        $query->where('daily_price_enabled', true)
          ->orWhere('monthly_price_enabled', true);
      })
      ->count();

    if ($availableProperties == 0) {
      return 0;
    }

    // Calculate total available rental days for last month
    $daysInLastMonth = $lastMonthStart->diffInDays($lastMonthEnd) + 1;
    $totalAvailableRentalDays = $availableProperties * $daysInLastMonth;

    // Get rentals that overlapped with last month
    $lastMonthRentals = PropertyRental::where(function ($query) use ($lastMonthStart, $lastMonthEnd) {
      $query->where(function ($q) use ($lastMonthStart, $lastMonthEnd) {
        $q->whereBetween('start_date', [$lastMonthStart, $lastMonthEnd]);
      })->orWhere(function ($q) use ($lastMonthStart, $lastMonthEnd) {
        $q->whereBetween('end_date', [$lastMonthStart, $lastMonthEnd]);
      })->orWhere(function ($q) use ($lastMonthStart, $lastMonthEnd) {
        $q->where('start_date', '<=', $lastMonthStart)
          ->where('end_date', '>=', $lastMonthEnd);
      });
    })->get();

    $actualRentedDays = 0;
    foreach ($lastMonthRentals as $rental) {
      $rentalStart = max($rental->start_date->startOfDay(), $lastMonthStart->startOfDay());
      $rentalEnd = min($rental->end_date->startOfDay(), $lastMonthEnd->startOfDay());

      if ($rentalStart <= $rentalEnd) {
        $daysInOverlap = $rentalStart->diffInDays($rentalEnd) + 1;
        $actualRentedDays += $daysInOverlap;
      }
    }

    return $totalAvailableRentalDays > 0
      ? min(($actualRentedDays / $totalAvailableRentalDays) * 100, 100)
      : 0;
  }
}
