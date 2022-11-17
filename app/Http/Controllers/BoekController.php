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
use App\Models\Lent_books;
use App\Models\Reservations;
use App\Models\Subscription;
use Session;
use Hash;
use Mail;

class BoekController extends Controller
{
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

        $boeken = Books::where('writer', 'LIKE', '%'.$schrijver.'%')->where('isbn', 'LIKE', '%'.$isbn.'%')->where('title', 'LIKE', '%'.$titel.'%')->where('genre', 'LIKE', '%'.$genre.'%')->paginate(10)->withQueryString();


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

    public function view_boek(Request $request) {
        $info = Books::where('id', '=', $request->id)->first();
        $count = Books::where('id', '=', $request->id)->count();
        $status = Reservations::where('book_id', '=', $request->id)->count();
        if ($status == 0) { 
            $status = Lent_books::where('book_id', '=', $request->id)->count();
            if ($status == 0) { 
                $status = 'Beschikbaar';
            }
            else {
                $status = 'Uitgeleend';
            }
        }
        else {
            $status = 'Gereserveerd';
        }
        if ($count == 0) {
            return redirect()->back();
        }
        if(Session()->has('loginId')) {
            $reserveren = $this->CheckRol('reservate_book');
            if ($reserveren == true) {
                $reserveren = $this->reserveren();
            }
            else {
                $reserveren = false;
            }
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('view_users');
            return view('boek', compact('account', 'user', 'info', 'status', 'reserveren'));
        }
        else {
            return view('boek', compact('info', 'status'));
        }
    }

    public function reservate_boek(Request $request) {
        if(Session()->has('loginId')) {
            $reserveren = $this->CheckRol('reservate_book');
            if ($reserveren == true) {
                $reserveren = $this->reserveren();
                if ($reserveren == true){
                    $id = Session::get('loginId');
                    $reservation = new Reservations();
                    $reservation->book_id = $request->id;
                    $reservation->user_id = $id;
                    $reservation->reservation_date = date('d-m-Y');
                    $res = $reservation->save();
                    if($res){
                        return back()->with('success','You have registered');
                    }else{
                        return back()->with('failed', 'Something went wrong');
                    }
                }
                else {
                    return redirect()->back();
                }
            }
            else {
                return redirect()->back();
            }
        }
        else {
            return redirect()->back();
        }
        
    }

    public function reserveren() {
        $id = Session::get('loginId');
        $lent_books = Lent_books::where('user_id', '=', $id)->count();
        $reservations = Reservations::where('user_id', '=', $id)->count();
        $books = $lent_books + $reservations;

        $subscription = User::where('id', '=', $id)->pluck('subscription_id')->first();
        $subscriptionCheck = Subscription::where('id', '=', $subscription)->pluck('books')->first();

        if ($books < $subscriptionCheck) {
            return true;
        } else {
            return false;
        }
    }
}


