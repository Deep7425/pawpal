<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('organizationMaster')) {
            // return redirect("adminv2")->send();
            // Return a custom response, such as JSON error or 403 Forbidden
            return redirect()->route('home');
            // return response()->json(['status' => 0, 'message' => 'Unauthorized access: session not set'], 403);
        } 

        return $next($request);
    }
}
