<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\CheckRolController;
use App\Mail\NotifyMail;
use App\Models\User;
use App\Models\Rollen;
use App\Models\Permissions;
use App\Models\Rol_perm;
use Session;
use Hash;
use Mail;

class LoginController extends Controller
{
    public function index(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('index', compact('account', 'user'));
        }
        else {
            return view('index');
        }
    }

    public function login(){
        return view('login');
    }

    public function loginUser(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            if($request->password == $user->password){
                $request->session()->put('loginId',$user->id);
                $request->session()->put('rol', $user->rol_id);
                return redirect('/');
            }else{
                return back()->with('failed', 'Password is incorrect');
            }
        }else {
            return back()->with('failed', 'This email is not registered');
        }
    }

    public function logout(){
        Session::pull('loginId');
        return redirect('/');
    }

    public function CheckRol($permdesc){
        $data = User::where('id', '=',Session::get('loginId'))->first();
        $rol_id = $data->rol_id;
        $permissions = Permissions::all()->pluck('perm_id');
        foreach ($permissions as $permissions) {
            $rol_perm = Rol_perm::all()->where('perm_id', $permissions)->where('rol_id', $rol_id)->pluck('perm_id');
            $rol_perm = implode(json_decode($rol_perm));
            $test2 = Permissions::all()->where('perm_id', $rol_perm)->pluck('perm_desc');
            $test2 = implode(json_decode($test2));
            if($test2 == $permdesc){
                return true;
            }
        }
    }

    public function gebruikers(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('gebruikers', compact('account', 'user'));
        }
        else {
            return view('gebruikers');
        }
    }

    public function account(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('account', compact('account', 'user'));
        }
        else {
            return view('account');
        }
    }

    public function boeken(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('boeken', compact('account', 'user'));
        }
        else {
            return view('boeken');
        }
    }

    public function zoeken(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('zoeken', compact('account', 'user'));
        }
        else {
            return view('zoeken');
        }
    }
}
