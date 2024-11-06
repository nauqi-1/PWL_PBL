<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        $user_role = $request->user()->getRole(); //ambil data level_kode dari user yg login

        if(in_array($user_role, $roles)) {  //cek apakah user punya role yang diinginkan
            return $next($request);
        }

        abort(403,'Forbidden. Insufficient Permissions.'); //tambah pesan error bagi user yang tidak memiliki level yg sesuai
    }
}
