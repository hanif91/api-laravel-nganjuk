<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoggingRequestMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {

        $log = Log::query()->create([
            "url" => $request->fullUrl()??'',
            "ip" => $request->getClientIp()??'',
            "method" => $request->getMethod()??'',
            "device" => $request->header('User-Agent')??'-',
            "parameters" => collect($request->input())->__toString(),
            "response" => 'Log Hit',
            "username" => auth()->check() ? auth()->user()->name : '',
        ]);
        if(is_null($log))
            abort(400,"Logging Failed..");

        return $next($request);
    }

    public function terminate(Request $request,Response $response)
    {

        Log::query()->create([
            "url" => $request->fullUrl(),
            "ip" => $request->getClientIp(),
            "method" => $request->getMethod(),
            "device" => $request->header('User-Agent')??'-',
            "parameters" => collect($request->input())->__toString(),
            "response" => $request->routeIs('v1.lpp') ? '' : ($response->getContent()??''),
            "username" => auth()->check() ? auth()->user()->name : '',
        ]);
    }
}
