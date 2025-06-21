<?php
namespace App\Exports;

use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Subscription::with(['provider:id,name', 'package:id,name'])
            ->select(['id', 'provider_id', 'package_duration','package_id', 'paid','package_amount','payment_method','status','end_subscribe','start_subscribe']);
    }

    public function headings(): array
    {
        return [ "Provider Name", "Package Name", "Duration", "Paid", "Amount", "Payment Method", "Status", "Start At", "End At"];
    }
    
    public function map($subscription): array
    {

        return [
            $subscription->provider->name ?? 'N/A',
            $subscription->package->name ?? 'N/A',
            $subscription->package_duration  ." Month",
            $subscription->paid,
            $subscription->package_amount,
            ucwords(str_replace('_', ' ', $subscription->payment_method)),
            $subscription->status ==1 ?"Active" :"Not Active",
            $subscription->start_subscribe,
            $subscription->end_subscribe,
        ];
    }
}
