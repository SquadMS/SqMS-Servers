<?php

namespace SquadMS\Servers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class WorkerAuth
{
    /**
     * Handle an incoming request and validate the worker auth token and host.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Config::get('sqms-servers.worker.auth_token') || $request->header('SQUADMS-WORKER-AUTH-TOKEN') !== Config::get('sqms-servers.worker.auth_token')) {
            return Response::json([
                'status' => false,
                'error'  => 'Unauthorized Token: '.$request->header('SQUADMS-WORKER-AUTH-TOKEN'),
            ], 401);
        }

        if (! Config::get('sqms-servers.worker.auth_ip') || $request->ip() !== Config::get('sqms-servers.worker.auth_ip')) {
            return Response::json([
                'status' => false,
                'error'  => 'Unauthorized IP: '.$request->ip(),
            ], 401);
        }

        return $next($request);
    }
}
