<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LangController extends Controller
{
    public function Localization( $lang){
        App::setLocale($lang);
        session(['lang' => $lang]);

        return back();
    }
}
