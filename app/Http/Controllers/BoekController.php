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
use App\Models\Subscription;
use Session;
use Hash;
use Mail;
use Carbon\Carbon;

class BoekController extends Controller
{
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
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('boeken', compact('account', 'user', 'boeken', 'genre', 'return'));
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
            $check = Lent_books::where('book_id', '=', $request->id)->count();
            if ($check == 0) {}
            else {
                $status = 'Uitgeleend & Gereserveerd';
            }
        }
        if ($count == 0) {
            return redirect()->back();
        }
        if(Auth::check()) {
            $id = Auth::id();
            $uitgeleend = Lent_books::where('book_id', '=', $request->id)->where('user_id', '=', $id)->first();
            $reserveren = $this->CheckRol('reservate_book');
            if ($reserveren == true) {
                $reserveren = $this->reserveren($id);
            }
            else {
                $reserveren = false;
            }
            $reserverenKlant = $this->CheckRol('reservate_book_client');
            $uitlenen = $this->CheckRol('lent_book');
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('boek', compact('account', 'user', 'info', 'status', 'reserveren', 'reserverenKlant', 'uitlenen', 'return', 'uitgeleend'));
        }
        else {
            $reserveren = false;
            $reserverenKlant = false;
            $uitlenen = false;
            return view('boek', compact('info', 'status', 'reserveren', 'reserverenKlant', 'uitlenen'));
        }
    }

    public function reservate_boek(Request $request) {
        if(Auth::check()) {
            $id = Auth::id();
            $reserveren = $this->CheckRol('reservate_book');
            if ($reserveren == true) {
                $uitgeleend = Lent_books::where('book_id', '=', $request->id)->where('user_id', '=', $id)->first();
 
                if ($uitgeleend) {
                    return redirect()->back()->with('success', 'U leent dit boek al');
                }
                else {
                    $reserveren = $this->reserveren($id);
                    if ($reserveren == true){
                        $id = Session::get('loginId');
                        $reservation = new Reservations();
                        $reservation->book_id = $request->id;
                        $reservation->user_id = $id;
                        $reservation->reservation_date = date('d-m-Y');
                        $res = $reservation->save();
                        if($res){
                            return redirect()->back()->with('success','Boek gereserveerd');
                        }
                    }
                    else {
                        return redirect()->back();
                    }
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

    public function reserveren($id) {
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

    public function reservate_book_client(Request $request) {
        $request->validate([
            'klant_id'=>'required',
            'book_id'=>'required'
        ]);
        $uitgeleend = Lent_books::where('book_id', '=', $request->book_id)->where('user_id', '=', $request->klant_id)->first();
        if ($uitgeleend) {
            return redirect()->back()->with('success','Klant leent boek al');
        }
        else {
            $check = Reservations::where('book_id', '=', $request->book_id)->first();
            if($check){
                return redirect()->back()->with('success','Boek is al gereserveerd');
            }
            else {
                $klant = User::where('id', '=', $request->klant_id)->first();
                if ($klant) {
                    $id = $klant->id;
                    $book_id = $request->book_id;
                    if ($klant->subscription_id)  {
                        $reserveren = $this->reserveren($id);
                        if ($reserveren == true) {
                            $reservation = new Reservations();
                            $reservation->book_id = $book_id;
                            $reservation->user_id = $id;
                            $reservation->reservation_date = date('d-m-Y');
                            $res = $reservation->save();
                            if($res){
                                return redirect()->back()->with('success','Boek gereserveerd');
                            }
                        }
                        else {
                            return redirect()->back()->with('success', 'Maximaal aantal boeken bereikt');
                        }
                    }
                    else {
                        return redirect()->back()->with('success', 'Klant kan geen boeken lenen');
                    }
                }
                else {
                    return redirect()->back()->with('success','Gebruiker bestaat niet');
                }
            }
        }
    }

    public function uitlenen(Request $request){
        $request->validate([
            'klant_id'=>'required',
            'book_id'=>'required'
        ]);
        $check = Lent_books::where('book_id', '=', $request->book_id)->first();
        if($check){
            return redirect()->back()->with('success','Boek is al uitgeleend');
        }
        else {
            $klant = User::where('id', '=', $request->klant_id)->first();
            if ($klant) {
                $id = $klant->id;
                $book_id = $request->book_id;

                $lent_books = Lent_books::where('user_id', '=', $id)->count();
                $reservations = Reservations::where('user_id', '=', $id)->count();
                $books = $lent_books + $reservations;

                $subscription = User::where('id', '=', $id)->pluck('subscription_id')->first();
                $subscriptionCheck = Subscription::where('id', '=', $subscription)->pluck('books')->first();
                $books =  --$books;
                if ($books < $subscriptionCheck) {
                    $boeken = true;
                } else {
                    $boeken = false;
                }
                if ($boeken == true){
                    $reservations = Reservations::where('book_id', '=', $request->book_id)->first();
                    if ($reservations) {
                        $checkReservations = Reservations::where('book_id', '=', $request->book_id)->where('user_id', '=', $request->klant_id)->first();
                        if ($checkReservations) {
                            $date = date('d-m-Y');
                            $carbon = new Carbon($date);
                            $lent_book = new Lent_books();
                            $lent_book->book_id = $book_id;
                            $lent_book->user_id = $id;
                            $lent_book->lent_date = $date;
                            $lent_book->return_date = $carbon->addDays(28)->format('d-m-Y');
                            $res = $lent_book->save();
                            if($res){
                                Reservations::where('book_id', '=', $request->book_id)->where('user_id', '=', $request->klant_id)->first()->delete();
                                return redirect()->back()->with('success','Boek uitgeleend');
                            }
                        }
                        else {
                            return redirect()->back()->with('success', 'Boek is al gereserveerd');
                        }
                    }
                    else {
                        $date = date('d-m-Y');
                        $carbon = new Carbon($date);
                        $lent_book = new Lent_books();
                        $lent_book->book_id = $book_id;
                        $lent_book->user_id = $id;
                        $lent_book->lent_date = $date;
                        $lent_book->return_date = $carbon->addDays(28)->format('d-m-Y');
                        $res = $lent_book->save();
                        if($res){
                                return redirect()->back()->with('success','Boek uitgeleend');
                        }
                    }
                }
                else {
                    return redirect()->back()->with('success', 'Maximaal aantal boeken bereikt');
                }
            }
            else {
                return redirect()->back()->with('success','Gebruiker bestaat niet');
            }
        }
    }

    public function boek_terug_brengen(Request $request) {
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');
        if (isset($request->user_id)) {
            $id = $request->user_id;
            $gebruiker = User::where('id', '=', $id)->first();
            if ($gebruiker) {
                if (isset($request->boek)) {
                    foreach ($request->boek as $boek) {
                        echo $boek;
                        $lent_book = Lent_books::where('user_id', '=', $id)->where('book_id', '=', $boek)->first();
                        if ($lent_book) {
                            Lent_books::where('user_id', '=', $id)->where('book_id', '=', $boek)->delete();
                        }
                        else {}
                    }
                    return redirect()->back()->with('success', 'Boeken terug gebracht');
                }
                else {
                    $name = $gebruiker->id;
                    $boeken = Lent_books::where('user_id', '=', $id)->with('book')->get();
                    return view('return_book', compact('account', 'user','return', 'name', 'boeken'));
                }
            }
            else {
                return redirect()->back()->with('success', 'Gebruiker niet gevonden');
            }
        }
        else {
            return view('return_book', compact('account', 'user','return'));
        }
    }
}


