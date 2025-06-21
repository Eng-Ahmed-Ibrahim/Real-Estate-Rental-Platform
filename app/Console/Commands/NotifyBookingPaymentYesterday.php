<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Console\Command;

class NotifyBookingPaymentYesterday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-booking-payment-yesterday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // $yesterday = Carbon::yesterday()->format('m/d/Y'); // تاريخ أمس بصيغة MM/DD/YYYY

        // $bookings_payments_delay = Booking::where('start_at', $yesterday)
        //     ->where('payment_status_id', 4) 
        //     ->get();

        // foreach ($bookings_payments_delay as $booking_payment_delay) {
        //     $booking_payment_delay->update([
        //         "payment_status_id" => 6, // تحديث الحالة إلى 3
        //     ]);
        // }
    }
}
