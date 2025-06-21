<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\testWebsockets;

class testWebsocketController extends Controller
{
    public function test(){
        event( new testWebsockets);
        return "runed";
    }
}
