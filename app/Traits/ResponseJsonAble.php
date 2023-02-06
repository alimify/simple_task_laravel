<?php

namespace App\Traits;

use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

trait ResponseJsonAble
{

    public function respond($success = false,$message = null,$data = null, $httpCode = HttpResponse::HTTP_OK)
    {
        if(!$message){
            $message = $success ? 'Successfully executed!' : 'Sorry, Operation failed!';
        }

        return Response::json([
            'success'   => $success,
            'message'   =>  $message,
            'data'      => $data,
        ], $httpCode);
    }
}