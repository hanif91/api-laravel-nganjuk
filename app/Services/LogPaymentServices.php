<?php


namespace App\Services;


use App\Models\Log;
use Illuminate\Http\Request;

class LogPaymentServices
{

    public static function createLog(Request $request,$type,$response ) : bool
    {

        // $type = ["success","error"];
        $log = Log::create([
            "url" => "Proses Save : ".$type,
            "ip" => $request->getClientIp()??'-',
            "method" => $request->getMethod()??'POST',
            "device" => $request->header('User-Agent')??'-',
            "parameters" => collect($request->input())->__toString(),
            "response" => $response,
            "username" => auth()->check() ? auth()->user()->name : '',
        ]);

        return !is_null($log);
    }

}
