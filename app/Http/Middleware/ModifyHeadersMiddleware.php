<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModifyHeadersMiddleware
{

    public function handle( $request, Closure $next )
{
    $response = $next( $request );
    $response->header( 'Access-Control-Allow-Origin', '*' );
    $response->header( 'Access-Control-Allow-Headers', 'Origin, Content-Type' );

    return $response;
}
}
