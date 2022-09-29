<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Internal
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
        if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Moderator' || auth()->user()->role == 'Developer' || auth()->user()->role == 'Editor' || auth()->user()->role == 'Corporate' || auth()->user()->role == 'Sales' || auth()->user()->role == 'RMA' || auth()->user()->role == 'Encoder' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Agent' || auth()->user()->role == 'Cashier' || auth()->user()->role == 'Technical' || auth()->user()->role == 'Cashier / Technical' || auth()->user()->role == 'Stockman') {
            return $next($request);
        }

        return back();
    }
}
