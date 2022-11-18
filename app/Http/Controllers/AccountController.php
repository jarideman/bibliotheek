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
use Session;
use Hash;
use Mail;
use Carbon\Carbon;

class AccountController extends Controller
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

    public function account(Request $request){
        $id = Session::get('loginId');
        $info = User::where('id', '=', $id)->first();
        $boeken = Books::all();
        $reservations = Reservations::where('user_id', '=', $id)->with('book')->with('book.uitlenen')->paginate(5)->withQueryString();
        $status = Lent_books::all();
        $lent_books = Lent_books::where('user_id', '=', $id)->with('book')->paginate(5)->withQueryString();
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('view_users');
        return view('account', compact('account', 'user', 'reservations', 'lent_books', 'boeken', 'status', 'info'));
    }

    public function verlengen(Request $request) {
        $book_id = $request->id;
        $id = Session::get('loginId');
        $book = Lent_books::where('book_id', '=', $book_id)->where('user_id', '=', $id)->first();

        $carbon = new Carbon($book->return_date);
        $return_date = $book->return_date;
        $new_return_date = $carbon->addDays(7)->format('d-m-Y');
        echo $return_date;
        echo $new_return_date;

        Lent_books::where('book_id', '=', $book_id)->first()->update(['return_date' => $new_return_date, 'times_extended' => 1]);
        return redirect('account')->with('success','Boek verlengd');
    }
}
