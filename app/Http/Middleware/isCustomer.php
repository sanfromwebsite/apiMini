<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isCustomer
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('sanctum');
        $roles = $user->role_id;
        if($roles != 1){
            return response()->json([
                'result' => false,
                'message' => 'can access only for customer',
                'data' => []
            ]);
        }
        return $next($request);
    }
}
