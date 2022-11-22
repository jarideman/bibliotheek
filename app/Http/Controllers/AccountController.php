<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
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
use App\Jobs\CheckLentBooks;
use App\Jobs\BoekTeLaat;


class AccountController extends Controller
{
    public function CheckRol($permdesc){
        $data = User::where('id', '=', Auth::id())->first();
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
        $id = Auth::id();
        $directory =  '/documenten/'. $id;
        $storage = Storage::allFiles($directory);
        $info = User::where('id', '=', $id)->first();
        $boeken = Books::all();
        $reservations = Reservations::where('user_id', '=', $id)->with('book')->with('book.uitlenen')->paginate(5)->withQueryString();

        $status = Lent_books::all();
        $lent_books = Lent_books::where('user_id', '=', $id)->with('book')->paginate(5)->withQueryString();
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');

        $subscription_id = $info->subscription_id;
        $abbonement = Subscription::where('id', '=', $subscription_id)->first();
        return view('account', compact('account', 'user', 'reservations', 'lent_books', 'boeken', 'status', 'info', 'return', 'abbonement', 'storage'));
    }

    public function verlengen(Request $request) {
        $book_id = $request->id;
        $id = Auth::id();
        $book = Lent_books::where('book_id', '=', $book_id)->where('user_id', '=', $id)->first();

        $carbon = new Carbon($book->return_date);
        $return_date = $book->return_date;
        $new_return_date = $carbon->addDays(7)->format('d-m-Y');
        echo $return_date;
        echo $new_return_date;

        Lent_books::where('book_id', '=', $book_id)->first()->update(['return_date' => $new_return_date, 'times_extended' => 1]);
        return redirect('account')->with('success','Boek verlengd');
    }

    public function abbonementwijzigen() {
        if (Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $id = Auth::id();
            $info = User::where('id', '=', $id)->first();
            $subscription_id = $info->subscription_id;
            if ($subscription_id) {
                $abbonement = Subscription::where('id', '=', $subscription_id)->first();
                $abbonementen = Subscription::all();
                return view('abbonementen', compact('account', 'user', 'return', 'abbonement', 'abbonementen'));
            }
            else {
                $abbonement = '';
                $abbonementen = Subscription::all();
                return view('abbonementen', compact('account', 'user', 'return', 'abbonement', 'abbonementen'));
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function afsluiten(Request $request) {
        if(Auth::check()) {
            $id = Auth::id();
            $lent_books = Lent_books::where('user_id', '=', $id)->count();
            $reservations = Reservations::where('user_id', '=', $id)->count();
            $reserveren = $lent_books + $reservations;
            if ($request->checkbox1) {
                $subscription_id= '1';
                $subscription = Subscription::where('id', '=', $subscription_id)->first();
                if($subscription->books >= $reserveren) {
                    $update = User::where('id', '=', $id)->update(array('subscription_id' => $subscription_id));
                    if ($update) {
                        return redirect('/account')->with('success', 'Abbonement succesvol gewijzigd');
                    }
                    else {
                        return redirect()->back()->with('success', 'Oeps er ging iets fout');
                    }
                }
                else {
                    return redirect()->back()->with('success', 'Te veel boeken geleend/gereserveerd voor dit abbonement');
                }
            }
            elseif ($request->checkbox2) {
                $subscription_id= '2';
                $subscription = Subscription::where('id', '=', $subscription_id)->first();
                if($subscription->books >= $reserveren) {
                    $update = User::where('id', '=', $id)->update(array('subscription_id' => $subscription_id));
                    if ($update) {
                        return redirect('/account')->with('success', 'Abbonement succesvol gewijzigd');
                    }
                    else {
                        return redirect()->back()->with('success', 'Oeps er ging iets fout');
                    }
                }
                else {
                    return redirect()->back()->with('success', 'Te veel boeken geleend/gereserveerd voor dit abbonement');
                }
            }
            elseif ($request->checkbox3) {
                $subscription_id= '3';
                $subscription = Subscription::where('id', '=', $subscription_id)->first();
                if($subscription->books >= $reserveren) {
                    $update = User::where('id', '=', $id)->update(array('subscription_id' => $subscription_id));
                    if ($update) {
                        return redirect('/account')->with('success', 'Abbonement succesvol gewijzigd');
                    }
                    else {
                        return redirect()->back()->with('success', 'Oeps er ging iets fout');
                    }
                }
                else {
                    return redirect()->back()->with('success', 'Te veel boeken geleend/gereserveerd voor dit abbonement');
                }
            }
            else {
                return redirect()->back()->with('success', 'Kies een van de abbonementen');
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function cancel(Request $request){
        $book_id = $request->id;
        $id = Auth::id();
        $reservation = Reservations::where('user_id', '=', $id)->where('book_id', '=', $book_id)->delete();
        if ($reservation) {
            return redirect()->back()->with('success', 'Reservatie gecanceld');
        }
        else {
            return redirect()->back()->with('success', 'Oeps er ging iets fout');
        }
    }

    public function fileUpload(Request $request){
        $request->validate([
            'file' => 'required',
            'id' => 'required'
        ]);
        $id = $request->id;
        $file = $request->file('file');
        if($request->file()) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $fileName)) {
                return back()->with('success','Bestandsnaam mag niet de volgende symbolen bevatten \'^£$%&*()}{@#~?><>,|=_+¬-/');
            }
            else {
                $path = $file->storeAs('public/images/'. $id, $fileName);
                User::where('id', '=', $id)->update(array('foto' => $fileName));
                return back()->with('success','Profiel foto geüploaded.');
            }
        }
    }

    public function check_boeken(){
        $date = date('d-m-Y');
        $carbon = new Carbon($date);
        $return_date = $carbon->addDays(4)->format('d-m-Y');
        $books = Lent_books::all();
        foreach ($books as $books){
            $user = User::where('id', $books->user_id)->first();
            $mail = $user->email;
            $test = new Carbon($books->return_date);
            $test2 = $test->format('d-m-Y');
            $timeStamp = strtotime($test2);
            $timeStamp2 = strtotime($return_date);
            $timeStamp3 = strtotime($date);
            if ($timeStamp == $timeStamp3){
                BoekTeLaat::dispatch($mail);
            }
            elseif ($timeStamp <= $timeStamp2) {
                CheckLentBooks::dispatch($mail);
            }
        }
    }
}
