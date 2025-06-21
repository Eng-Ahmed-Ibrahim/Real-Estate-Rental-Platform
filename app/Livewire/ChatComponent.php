<?php

namespace App\Livewire;

use App\Models\Feature;
use Livewire\Component;
use App\Events\FeaturesEvent;

class ChatComponent extends Component
{
    public $feature_name=null;
    public $feature_name_ar=null;
    public $message=null;
    public function render()
    {
        $features=Feature::orderBy("id","DESC")->get();

        return view('livewire.chat-component')
        ->with("features",$features)
        ;
    }

    public function store(){
        $feature=Feature::create([
            "feature_name_ar"=>$this->feature_name_ar,
            "feature_name"=>$this->feature_name,
        ]);
        broadcast(new FeaturesEvent($feature))->toOthers();
        $this->message="Added Successfully";
    }
}
