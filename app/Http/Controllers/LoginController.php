<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\CheckRolController;
use App\Mail\NotifyMail;
use App\Models\User;
use App\Models\Rols;
use App\Models\Permissions;
use App\Models\Rol_perm;
use App\Models\Books;
use App\Models\Notifications;
use Session;
use Hash;
use Mail;

class LoginController extends Controller
{
    public function index(){
        $date = date('d-m-Y');
        $meldingen = Notifications::where('start_date', '<=', $date)->where('end_date', '>=', $date)->get();
        $newBooks = Books::orderBy('purchase_date', 'DESC')->take(10)->get();
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('index', compact('account', 'user', 'meldingen', 'newBooks'));
        }
        else {
            return view('index', compact('meldingen', 'newBooks'));
        }
    }

    public function login(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('login', compact('account', 'user'));
        }
        else {
            return view('login');
        }
    }

    public function loginUser(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
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

    public function registration(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('registration', compact('account', 'user'));
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

    public function boeken(Request $request){
        if ($request->schrijver) {
            $schrijver = $request->schrijver;
        } else {$schrijver = '';}

        if ($request->isbn) {
            $isbn = $request->isbn;
        } else {$isbn = '';}

        if ($request->titel) {
            $titel = $request->titel;
        } else {$titel = '';}

        if ($request->genre) {
            $genre = $request->genre;
        } else {$genre = '';}

        $boeken = Books::where('writer', 'LIKE', '%'.$schrijver.'%')->where('isbn', 'LIKE', '%'.$isbn.'%')->where('title', 'LIKE', '%'.$titel.'%')->where('genre', 'LIKE', '%'.$genre.'%')->get();
        $genre = Books::distinct()->pluck('genre');
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('boeken', compact('account', 'user', 'boeken', 'genre'));
        }
        else {
            return view('boeken', compact('boeken', 'genre'));
        }
    }

    public function zoeken(Request $request){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('zoeken', compact('account', 'user'));
        }
        else {
            return view('zoeken');
        }
    }

    public function view_boek(Request $request) {
        $info = Books::where('id', '=', $request->id)->first();
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('boek', compact('account', 'user', 'info'));
        }
        else {
            return view('boek', compact('info'));
        }
    }

}
