<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function register(): void
    {
//        $this->reportable(function (Throwable $e) {
//            //
//        });


        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {

                $message = (app()->isLocal() ? $e->getMessage() : 'Internal Server Error');
                $code = 500;
                if($e instanceof RouteNotFoundException){
                    $message = 'Route not avaliable or Invalid token.';
                    $code = 412;
                }

                if($e instanceof AuthenticationException){
                    $message = 'Not Authenticated';
                    $code = 401;
                }


                if($e instanceof HttpException){
                    $message = 'Invalid Request.';
                    $code = 400;
                }


                return response()->json([
                    'message' => $message,
                    'data' => null
                ],$code);
            }
        });


    }
}
