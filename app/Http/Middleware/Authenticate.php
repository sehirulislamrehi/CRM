<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @param Closure $next
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('super_admin')->check()) {
            return $next($request);
        } elseif (auth('web')->check()) {
            return $next($request);
        } else {
            if($request->route()->getName()==="ticket.agent"){
                $phone=$request->query('phone_number');
                $agent=$request->query('agent');
                $channel=$request->query('channel');
                // Flash the current URL along with the manually constructed query string to the session
                $request->session()->flash('previous_url', $request->url().'?'.'phone_number='.$phone.'&agent='.$agent.'&channel='.$channel);
            }
            return redirect()->route('admin.show-login');
        }
    }
}
