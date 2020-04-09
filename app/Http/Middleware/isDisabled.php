<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class isDisabled
{

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if($user && $user->accDisabled == 1){
            Auth::logout();

            $message = 'Your account has been suspended. Please contact administrator for more info.';
            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
