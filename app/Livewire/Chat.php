<?php

namespace App\Livewire;

use App\Models\Feature;
use Livewire\Component;

class Chat extends Component
{
    public $num=10;
    public function render()
    {
        $features=Feature::orderBy("id","DESC")->get();
        return view('livewire.chat-component')
        ->with("features",$features)
        ;
    }

}

