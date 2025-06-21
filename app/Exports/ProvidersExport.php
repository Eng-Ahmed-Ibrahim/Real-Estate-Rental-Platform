<?php

namespace App\Exports;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProvidersExport implements FromView,ShouldAutoSize
{
    use Exportable;
    private $data=[];
    public function __construct()
    {   
        $this->data=User::where("power","provider")->orderBy("id",'DESC')->get();
    }
    public function view() : View
    {
        return view('admin.exports.providers')->with('providers',$this->data);
    }
}
