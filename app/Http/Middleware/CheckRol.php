<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rollen;
use App\Models\Permissions;
use App\Models\Rol_perm;
use Session;

class CheckRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permdesc)
    {
        if(Session()->has('loginId')){
            $data = User::where('id', '=',Session::get('loginId'))->first();
            $rol_id = $data->rol_id;
            $permissions = Permissions::all()->pluck('perm_id');
            foreach ($permissions as $permissions) {
                $rol_perm = Rol_perm::all()->where('perm_id', '=', $permissions)->where('rol_id', '=', $rol_id)->pluck('perm_id');
                $rol_perm = implode(json_decode($rol_perm));
                $test2 = Permissions::all()->where('perm_id', $rol_perm)->pluck('perm_desc');
                $test2 = implode(json_decode($test2));
                if($test2 == $permdesc){
                    return $next($request);
                }
            }
            
            return redirect('/logout');
        }
        else {
            return redirect('/');
        }
    }
}
