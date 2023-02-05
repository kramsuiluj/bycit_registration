<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveEmptyGetRequests
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
        $query = collect(request()->query)->toArray();
        $queryCount = count($query);

        foreach ($query as $param => $value) {
            if (! isset($value) || $value == '') {
                unset($query[$param]);
            }
        }

        if ($queryCount > count($query)) {
            return redirect()->route($request->route()->getName(), $query);
        }

        return $next($request);
    }
}
