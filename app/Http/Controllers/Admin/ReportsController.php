<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class ReportsController extends Controller
{
    public function payment_reports(Request $request)
    {
        $today = Carbon::today();

        $query = Payment::query();
        if ($request->filled('from') && $request->filled('to')) {
            $startDate = Carbon::parse($request->from);
            $endDate = Carbon::parse($request->to);
            $query->whereBetween("created_at", [$startDate, $endDate]);
        }

        if ($request->filled('date')) {
            if ($request->date == 'daily') {
                $query->daily();
            } elseif ($request->date == 'weekly') {
                $query->weekly();
            } elseif ($request->date == 'monthly') {
                $query->monthly();
            }
        }
        $payments = $query->with(['customer', 'payment_method'])->orderBy("id", "DESC")->paginate(15);
        return view('admin.reports.payment')
            ->with("date", $request->date)
            ->with("from", $request->from)
            ->with("to", $request->to)

            ->with("payments", $payments);
    }
    public function show($invoice_id)
    {

        $payment = Payment::where("invoice_id", $invoice_id)->with(['customer', 'payment_method'])->first();
        if (!$payment)
            return back();
        return view('admin.reports.view')
            ->with('payment', $payment);
    }
    public function generate_pdf($invoice_id)
    {
        $invoice = Payment::where("invoice_id", $invoice_id)->with(['customer', 'payment_method'])->first();
        if (! $invoice)
            return back();
        $company_information=Setting::find(1);    
        $data = [
            'invoice' => $invoice,
            'company_information' => $company_information,
        ];
        $pdf = PDF::loadView('admin.payment.invoice', $data);
        return $pdf->download("INV#$invoice->invoice_id.pdf");
    }
    public function view_pdf($invoice_id)
    {
        $invoice = Payment::where("invoice_id", $invoice_id)->with(['customer', 'payment_method'])->first();
        if (! $invoice)
            return back();
        $company_information=Setting::find(1);    
        $data = [
            'invoice' => $invoice,
            'company_information' => $company_information,
        ];
        $pdf = PDF::loadView('admin.payment.invoice', $data);
        return $pdf->stream("INV#$invoice->invoice_id.pdf");
    }
}
