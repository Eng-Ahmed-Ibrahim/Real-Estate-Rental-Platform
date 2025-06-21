<?php

namespace App\Exports;

use App\Models\Subscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscribersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Subscriber::all(['id', 'name', 'email', 'phone', 'created_at']);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Created At'];
    }
}
