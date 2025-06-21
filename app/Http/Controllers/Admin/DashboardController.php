<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Service;
use App\Models\Category;
use App\Models\Reminder;
use App\Models\Categories;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{


    public function index(Request $request)
    {
        Cache::forget('dashboard_data'); // Clear cache for fresh data
        $data = Cache::remember('dashboard_data', 60 * 1, function () {

            $total_pending_services = Service::where('accept', 0)->count();
            $total_categories = Categories::count();

            $allMonthsEn = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            $allMonthsAr = [
                'يناير',
                'فبراير',
                'مارس',
                'أبريل',
                'مايو',
                'يونيو',
                'يوليو',
                'أغسطس',
                'سبتمبر',
                'أكتوبر',
                'نوفمبر',
                'ديسمبر'
            ];

            $allMonths = session('lang') == 'en' ? $allMonthsEn : $allMonthsAr;

            $usersByMonth = User::selectRaw('COUNT(*) as count, MONTHNAME(created_at) as month, MONTH(created_at) as month_number')
                ->whereYear('created_at', Carbon::now()->year)
                ->where("power", "customer")
                ->groupBy('month', 'month_number')
                ->orderBy('month_number')
                ->get()
                ->keyBy('month');

            $bookingsByMonth = Booking::selectRaw('COUNT(*) as count, MONTHNAME(created_at) as month, MONTH(created_at) as month_number')
                ->where("payment_status_id", 3)
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month', 'month_number')
                ->orderBy('month_number')
                ->get()
                ->keyBy('month');

            $earningsData = Earning::selectRaw('YEAR(created_at) as year, MONTHNAME(created_at) as month, 
            SUM(admin_earning) as total_admin_earning, 
            SUM(provider_earning) as total_provider_earning')
                ->groupBy('year', 'month')
                ->orderByRaw('MIN(created_at)')
                ->get()
                ->groupBy('month')
                ->map(function ($month) {
                    $admin = $month->sum('total_admin_earning');
                    $provider = $month->sum('total_provider_earning');
                    return [
                        'admin' => $admin,
                        'provider' => $provider,
                        'total_admin_provider_earning' => $admin + $provider
                    ];
                });

            $earnings = collect($allMonthsEn)->mapWithKeys(function ($month) use ($earningsData) {
                return [$month => $earningsData->get($month, ['admin' => 0, 'provider' => 0, 'total_admin_provider_earning' => 0])];
            });

            $adminEarnings = $earnings->pluck('admin')->toArray();
            $providerEarnings = $earnings->pluck('provider')->toArray();
            $totalAdminProviderEarning = $earnings->pluck('total_admin_provider_earning')->map(fn($v) => $v ?? 0)->toArray();

            $currentMonthName = Carbon::now()->format('F');
            $currentMonthEarnings = $earnings->get($currentMonthName, ['admin' => 0, 'provider' => 0]);

            $months = [];
            $counts = [];
            $bookingCountsArr = [];

            foreach ($allMonths as $month) {
                $months[] = $month;
                $counts[] = isset($usersByMonth[$month]) ? $usersByMonth[$month]->count : 0;
                $bookingCountsArr[] = isset($bookingsByMonth[$month]) ? $bookingsByMonth[$month]->count : 0;
            }

            $topServices = Service::withCount(['booking' => function ($query) {
                $query->where('payment_status_id', 3);
            }])
                ->orderBy('booking_count', 'desc')
                ->take(5)
                ->get()
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => session('lang') == 'en' ?  $service->name : $service->name_ar,
                        'category' => session('lang') == 'en' ?  $service->category->brand_name : $service->category->brand_name_ar,
                        'image' => $service->image,
                        'booking_count' => $service->booking_count,
                    ];
                });

            $topUsers = Earning::select('user_id', DB::raw('SUM(provider_earning) as total_earning'))
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('user_id')
                ->orderBy('total_earning', 'desc')
                ->take(5)
                ->get();

            $topProviderNames = $topUsers->map(function ($earning) {
                return [
                    "name" => $earning->user->name,
                    "phone" => $earning->user->phone,
                    "image" => $earning->user->image,
                    "id" => $earning->user->id,
                    'total_earning' => $earning->total_earning,
                ];
            });

            $currentMonthNum = now()->month;
            $currentYear = now()->year;

            $bookingStatusCounts = Booking::selectRaw('booking_status_id, COUNT(*) as total')
                ->whereMonth('created_at', $currentMonthNum)
                ->whereYear('created_at', $currentYear)
                ->groupBy('booking_status_id')
                ->pluck('total', 'booking_status_id');

            $countEachBookingSection = [
                'total_booking_pending' => ["status" => 1, "section" => $bookingStatusCounts[1] ?? 0],
                'total_booking_accpet' => ["status" => 3, "section" => $bookingStatusCounts[3] ?? 0],
                'total_booking_rejected' => ["status" => 4, "section" => $bookingStatusCounts[4] ?? 0],
                'total_booking_cancelled' => ["status" => 5, "section" => $bookingStatusCounts[5] ?? 0],
                'total_booking_overview_time' => ["status" => 6, "section" => $bookingStatusCounts[6] ?? 0],
                'total_booking_overview_payment_time' => ["status" => 7, "section" => $bookingStatusCounts[7] ?? 0],
            ];
            $topCustomers = Booking::select('customer_id')
                ->selectRaw('COUNT(*) as total_booked')
                ->where("payment_status_id",3)
                ->groupBy('customer_id')
                ->orderByDesc('total_booked')
                ->with('customer') // eager load user info
                ->limit(5)
                ->get();
            
            $total_customers = User::where("power", "customer")->count();

                
            return [
                'labels' => $allMonths,
                'topCustomers' => $topCustomers,
                'totalAdminProviderEarning' => $totalAdminProviderEarning,
                'currentMonthEarnings' => $currentMonthEarnings,
                'currentMonth' => $allMonths[$currentMonthNum - 1],
                'earnings' => $earnings,
                'providerEarnings' => $providerEarnings,
                'adminEarnings' => $adminEarnings,
                'countEachBookingSection' => $countEachBookingSection,
                'topServices' => $topServices,
                'topProviderNames' => $topProviderNames,
                'months' => $months,
                'counts' => $counts,
                'bookingCounts' => $bookingCountsArr,
                'totalPendingServices' => $total_pending_services,
                'totalCategories' => $total_categories,
                "total_customers"=>$total_customers,
            ];
        });

        // Pass the cached data to the view with consistent keys:
        return view('admin.dashboard', [
            'labels' => $data['labels'],
            'topCustomers' => $data['topCustomers'],
            'total_admin_provider_earning' => $data['totalAdminProviderEarning'],
            'currentMonthEarnings' => $data['currentMonthEarnings'],
            'currentMonth' => $data['currentMonth'],
            'earnings' => $data['earnings'],
            'providerEarnings' => $data['providerEarnings'],
            'adminEarnings' => $data['adminEarnings'],
            'count_each_booking_section' => $data['countEachBookingSection'],
            'topServices' => $data['topServices'],
            'Top_provider_names' => $data['topProviderNames'],
            'months' => $data['months'],
            'counts' => $data['counts'],
            'bookingCounts' => $data['bookingCounts'],
            'total_pending_services' => $data['totalPendingServices'],
            'total_categories' => $data['totalCategories'],
            "total_customers" => $data['total_customers'],
        ]);
    }
}
