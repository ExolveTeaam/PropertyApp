<?php

namespace App\Http\Middleware;

use App\Core\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeInspector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== RoleEnum::PROPERTYINSPECTOR->value || $request->user()->role == null){
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        return $next($request);
    }
}
