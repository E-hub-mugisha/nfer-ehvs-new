<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        AuditLog::create([

            'user_id' => auth()->id(),

            'action' => 'PAGE_VISIT',

            'description' => $request->path(),

            'ip_address' => $request->ip()
        ]);

        return $next($request);
    }
}
