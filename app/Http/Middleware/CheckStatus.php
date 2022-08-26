<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Login Middleware
        $status = Auth::user()->Status;
        if ($status != 'مفعل'){
            session()->flash('expire','أنتهت مده صلاحيه المستخدم');
            return redirect()->route('Login.status');
        }
        return $next($request);
    }
}
