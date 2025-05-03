<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('sanctum');
        $roles = $user->role_id;
        if($roles != 2){
            return response()->json([
                'result' => false,
                'message' => 'can access only for admin',
                'data' => []
            ]);
        }
        return $next($request);
    }
}
