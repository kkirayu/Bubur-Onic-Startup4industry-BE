<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModifyHeadersMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $request->merge(['perusahaan_id' => 1, 'cabang_id' => 1]);
 
        return $next($request);
    }
}
