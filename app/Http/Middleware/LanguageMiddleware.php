<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $language = session('locale');
        Log::info($language);

        // Set application locale based on session
        if ($language) {
            App::setLocale($language);
        }
        else{
            App::setLocale('eng');
        }

        return $next($request);
    }
}
