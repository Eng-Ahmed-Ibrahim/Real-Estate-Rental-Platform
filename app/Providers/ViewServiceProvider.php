<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\Support;
use App\Models\Reminder;
use App\Models\Transactions;
use App\Models\WithdrawEarning;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $bookingCounts = Booking::selectRaw('booking_status_id, COUNT(*) as count')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('booking_status_id')
            ->pluck('count', 'booking_status_id');
        $total_booking_all = $bookingCounts->sum();
        $total_booking_pending = $bookingCounts[1] ?? 0;
        $total_booking_in_progress = $bookingCounts[2] ?? 0;
        $total_booking_accpet = $bookingCounts[3] ?? 0;
        $total_booking_rejected = $bookingCounts[4] ?? 0;
        $total_booking_cancelled = $bookingCounts[5] ?? 0;
        $total_booking_overview_time = $bookingCounts[6] ?? 0;
        $total_booking_overview_payment_time = $bookingCounts[7] ?? 0;

        $userCounts = User::selectRaw('power, COUNT(*) as count')
            ->groupBy('power')
            ->pluck('count', 'power');

        $total_admin_user = $userCounts['admin'] ?? 0;
        $total_provider_user = $userCounts['provider'] ?? 0;
        $total_customer_user = $userCounts['customer'] ?? 0;
        $total_employee_user = User::whereNotIn('power', ['customer', 'provider', 'admin'])->count(); // this stays


        $withdrawal_requests = WithdrawEarning::where("status", 2)->count();
        $not_seen_count = Support::where("seen", 0)->count();
        $website_logo = Setting::find(1)->website_logo;
        $total_deposit_requests = Transactions::where("status", 2)->count();


        $shared_data = [
            "total_booking_pending" => $total_booking_pending,
            "total_booking_in_progress" => $total_booking_in_progress,
            "total_booking_accpet" => $total_booking_accpet,
            "total_booking_rejected" => $total_booking_rejected,
            "total_booking_cancelled" => $total_booking_cancelled,
            "total_booking_overview_payment_time" => $total_booking_overview_payment_time,
            "total_booking_overview_time" => $total_booking_overview_time,
            "total_booking_all" => $total_booking_all,
            "total_admin_user" => $total_admin_user,
            "total_provider_user" => $total_provider_user,
            "total_customer_user" => $total_customer_user,
            "total_employee_user" => $total_employee_user,
            "withdrawal_requests" => $withdrawal_requests,
            "not_seen_count" => $not_seen_count,
            "website_logo" => $website_logo,
            "total_deposit_requests" => $total_deposit_requests,
        ];

        // Share the data with all views
        View::share('shared_data', $shared_data);
    }
}
