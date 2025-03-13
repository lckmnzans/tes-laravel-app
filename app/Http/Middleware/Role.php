<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
{
    if (!$request->user()) {
        return redirect('/')->with('error', 'Anda harus login untuk mengakses halaman ini.');
    }

    if ($request->user()->role !== $role) {
        return back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }

    return $next($request);
}
}
