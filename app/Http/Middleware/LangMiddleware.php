<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Reminder;
use Carbon\Carbon;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('lang')) {
            App::setLocale(session('lang')); // Set locale from session
        } else {
            session()->put("lang", "en");
            App::setLocale(session('lang')); // Set locale from session
        }
        if (Auth::check()) {
            $remind_me_later = Carbon::now()->subHour(); //  updated 11
            $reminders = Reminder::where('user_id', Auth::user()->id)
                ->where('date', date('Y-m-d'))
                ->where("seen", false)
                ->where(function ($query) use ($remind_me_later) {
                    $query->whereNull('updated_at')
                        ->orWhere('updated_at', '<', $remind_me_later);
                })
                ->get();
                View::share('reminders', $reminders);
        }

        // Share the reminders with all views

        return $next($request);
    }
}
