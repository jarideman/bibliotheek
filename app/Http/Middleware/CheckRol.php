<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Rollen;
use App\Models\Permissions;
use App\Models\Rol_perm;
use App\Models\Books;
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
        if(Auth::check()){
            $data = User::where('id', Auth::id())->first();
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
            
            return redirect()->back();
        }
        else {
            return redirect('/');
        }
    }
}
