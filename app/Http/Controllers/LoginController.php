<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckRolController;
use App\Mail\NotifyMail;
use App\Models\User;
use App\Models\Rols;
use App\Models\Permissions;
use App\Models\Rol_perm;
use App\Models\Books;
use App\Models\Notifications;
use App\Models\Lent_books;
use App\Models\Reservations;
use Session;
use Hash;
use Mail;

class LoginController extends Controller
{
    public function index(){
        $date = date('d-m-Y');
        $meldingen = Notifications::where('start_date', '<=', $date)->where('end_date', '>=', $date)->whereNull('rol_id')->get();
        $newBooks = Books::orderBy('purchase_date', 'DESC')->take(10)->get();
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $melding = Notifications::where('start_date', '<=', $date)->where('end_date', '>=', $date)->where('rol_id', Auth::user()->rol_id)->get();
            return view('index', compact('account', 'user', 'meldingen', 'melding', 'newBooks', 'return'));
        }
        else {
            return view('index', compact('meldingen', 'newBooks'));
        }
    }

    public function login(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('login', compact('account', 'user', 'return'));
        }
        else {
            return view('login');
        }
    }

    public function loginUser(Request $request){
        $validated = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            if(Auth::attempt($validated)){
                return redirect('/');
            }else{
                return back()->with('failed', 'Password is incorrect');
            }
        }else {
            return back()->with('failed', 'This email is not registered');
        }
    }

    public function registration(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('registration', compact('account', 'user', 'return'));
        }
        else {
            return view("registration");
        }
    }

    public function registerUser(Request $request){
        $request->validate([
            'naam'=>'required',
            'achternaam'=>'required',
            'adres'=>'required',
            'postcode'=>'required',
            'plaats'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        $user = new User();
        $user->name = $request->naam;
        $user->middlename = $request->tussenvoegsels;
        $user->surname = $request->achternaam;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->adres = $request->adres;
        $user->postcode = $request->postcode;
        $user->city = $request->plaats;
        $res = $user->save();
        if($res){
            return back()->with('success','You have registered');
        }else{
            return back()->with('failed', 'Something went wrong');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function CheckRol($permdesc){
        $data = User::where('id', Auth::id())->first();
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

}
