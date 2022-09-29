<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Accounting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Developer' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales' || auth()->user()->role == 'RMA' || auth()->user()->role == 'Cashier') {
            return $next($request);
        }

        return back();
    }
}
