<?php 
namespace App\Http\Controllers\API;

trait ResponseTrait{
    public function Response($data=null,$msg=null,$status){
        $array=[
            "data"=>$data,
            "msg"=>$msg,
            "status"=>$status,
        ];
        return response($array,$status);
    }
}