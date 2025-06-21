<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Exports\BookingExport;
use App\Exports\ProvidersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertiesDataExport;
use Carbon\Carbon;

class ExportsController extends Controller
{
    public function properties(){
        return Excel::download(new PropertiesDataExport,"properties.xlsx");
    }
    public function providers(){
        return Excel::download(new ProvidersExport,"providers.xlsx");
    }
    public function booking(Request $request){
                
        $query = Booking::query();

        if ($request->filled('date')) {
            if ($request->date == 'daily') {
                $query->daily(); 
            } elseif ($request->date == 'weekly') {
                $query->weekly();
            } elseif ($request->date == 'monthly') {
                $query->monthly(); 
            }
        }
        if ($request->filled('from') && $request->filled('to')) {
                $startDate = Carbon::parse($request->from);
                $endDate = Carbon::parse($request->to);
                $query->whereBetween("created_at", [$startDate, $endDate]);
        }
        $bookings = $query->orderBy("id", "DESC")->with(['provider','category','service','customer'])->get();
        return Excel::download(new BookingExport($bookings),"Bookings.xlsx");
    }
}
