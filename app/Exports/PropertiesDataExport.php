<?php

namespace App\Exports;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PropertiesDataExport implements FromView,ShouldAutoSize
{
    use Exportable;
    private $data=[];
    public function __construct()
    {   
        $this->data=Service::orderBy("id",'DESC')->with(['user','category'])->get();
    }
    public function view() : View
    {
        return view('admin.exports.properties')->with('services',$this->data);
    }
}
