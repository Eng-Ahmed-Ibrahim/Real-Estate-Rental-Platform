<?php

namespace App\Exports;
use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookingExport implements FromView,ShouldAutoSize
{
    use Exportable;
    private $data=[];
    public function __construct($data)
    {   
        $this->data=$data;
    }
    public function view() : View
    {
        return view('admin.exports.booking')->with('bookings',$this->data);
    }
}
