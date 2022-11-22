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

class BeheerController extends Controller
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

    public function beheer(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::with('rol')->paginate(5);
            return view('beheer', compact('account', 'user', 'return', 'gebruikers'));
        }
        else {
            return redirect()->back();
        }
    }

    public function viewuser(Request $request){
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');
        $info = User::where('id', '=', $request->id)->first();
        if ($info->subscription_id) {
            $abbonement = Subscription::where('id', '=', $info->subscription_id)->first();
            return view('viewuser', compact('account', 'user', 'return', 'info', 'abbonement'));
        }
        else {
            return view('viewuser', compact('account', 'user', 'return', 'info'));
        }
    }

    public function editgebruiker(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::with('rol')->paginate(5);
            return view('editgebruiker', compact('account', 'user', 'return', 'gebruikers'));
        }
        else {
            return redirect()->back();
        }
    }

    public function edituser(Request $request) {
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');
        $info = User::where('id', '=', $request->id)->first();
        $rollen = Rols::all();
        if ($info->subscription_id) {
            $abbonementen = Subscription::all();
            $abbonement = Subscription::where('id', '=', $info->subscription_id)->first();
            return view('edituser', compact('account', 'user', 'return', 'info', 'abbonement', 'abbonementen', 'rollen'));
        }
        else {
            return view('edituser', compact('account', 'user', 'return', 'info', 'rollen'));
        }
    }

    public function edit(Request $request){
        $id = $request->id;
        $lent_books = Lent_books::where('user_id', '=', $id)->count();
        $reservations = Reservations::where('user_id', '=', $id)->count();
        $books = $lent_books + $reservations;

        $subscriptionCheck = Subscription::where('id', $request->subscription_id)->pluck('books')->first();

        if ($books < $subscriptionCheck) {
            $update = User::where('id', $id)->update($request->except('_token'));
            if ($update){
                return redirect('editgebruiker')->with('success','Gebruiker bewerkt');
            }
            else {
                return redirect()->back()->with('success','Er ging iets fout');
            }
        } else {
            return redirect()->back()->with('success','Gebruiker heeft teveel boeken geleend/gereserveerd voor dit abbonement');
        }

    }

    public function deletegebruiker(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::with('rol')->paginate(5);
            return view('deletegebruiker', compact('account', 'user', 'return', 'gebruikers'));
        }
        else {
            return redirect()->back();
        }
    }

    public function deleteuser(Request $request){
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');
        $info = User::where('id', '=', $request->id)->first();
        if ($info->subscription_id) {
            $abbonement = Subscription::where('id', '=', $info->subscription_id)->first();
            return view('deleteuser', compact('account', 'user', 'return', 'info', 'abbonement'));
        }
        else {
            return view('deleteuser', compact('account', 'user', 'return', 'info'));
        }
    }

    public function delete(Request $request) {
        $id = $request->id;
        $delete = User::where('id', $id)->delete();
        if ($delete){
            $lent_books = Lent_books::where('user_id', $id)->delete();
            $reservations = Reservations::where('user_id', $id)->delete();
            return redirect('deletegebruiker')->with('success','Gebruiker verwijderd');
        }
        else {
            return redirect()->back()->with('success','Er ging iets fout');
        }
    }

    public function newgebruiker(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::all();
            $rollen = Rols::all();
            return view('newgebruiker', compact('account', 'user', 'return', 'gebruikers', 'rollen'));
        }
        else {
            return redirect()->back();
        }
    }

    public function new(Request $request) {
        $request->validate([
            'name'=>'required',
            'surname'=>'required',
            'adres'=>'required',
            'postcode'=>'required',
            'city'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        $user = User::create($request->except('file'));
        if($user){;
            $id = $user->id;
            $password = Hash::make($request->password);
            $password = $user->update(['password' => $password]);
            $file = $request->file('file');
            if($request->file()) {
                $fileName = time().'_'.$request->file->getClientOriginalName();
                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $fileName)) {
                    return back()->with('success','Bestandsnaam mag niet de volgende symbolen bevatten \'^£$%&*()}{@#~?><>,|=_+¬-/');
                }
                else {
                    $path = $file->storeAs('public/images/'. $id, $fileName);
                    User::where('id', $id)->update(array('foto' => $fileName));
                    return back()->with('success','Gebruiker opgeslagen');
                }
            }
            return back()->with('success','Gebruiker opgeslagen');
        }else{
            return back()->with('success', 'Something went wrong');
        }
    }

    public function abbonement(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $abbonementen = Subscription::paginate(4);
            return view('abbonement', compact('account', 'user', 'return', 'abbonementen'));
        }
        else {
            return redirect()->back();
        }
    }

    public function editabbonement(Request $request){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $abbonementen = Subscription::where('id', $request->id)->first();
            return view('editabbonement', compact('account', 'user', 'return', 'abbonementen'));
        }
        else {
            return redirect()->back();
        }
    }

    public function updateabbonement(Request $request){
        if(Auth::check()) {
            $update = Subscription::where('id', $request->id)->update($request->except('_token', 'id'));
            if ($update){
                return redirect('abbonementen')->with('success','Abbonement bewerkt');
            }
            else {
                return redirect()->back()->with('success','Er ging iets fout');
            }
        }
        else {
            return redirect()->back();
        }
    }

    public function addabbonement(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('addabbonement', compact('account', 'user', 'return'));
        }
        else {
            return redirect()->back();
        }   
    }

    public function newabbonement(Request $request) {
        if(Auth::check()) {
            $add = Subscription::create($request->all());
            if ($add) {
                return redirect('abbonementen')->with('success', 'Abbonement toegevoegd');
            }
            else {
                return back()->with('success', 'Er ging iets fout');
            }
        }
        else {
            return redirect()->back();
        }   
    }

    public function deleteabbonement(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $abbonementen = Subscription::paginate(4);
            return view('deleteabbonement', compact('account', 'user', 'return', 'abbonementen'));
        }
        else {
            return redirect()->back();
        }      
    }

    public function delabbonement(Request $request) {
        if(Auth::check()) {
            $delete = Subscription::where('id', $request->id)->delete();
            if ($delete){
                return redirect('abbonementen')->with('success', 'Abbonement verwijderd');
            }
            else {
                return back()->with('success', 'Er ging iets fout');
            }
        }
        else {
            return redirect()->back();
        }  
    }

    public function meldingen(){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $meldingen = Notifications::paginate(4);
            return view('meldingen', compact('account', 'user', 'return', 'meldingen'));
        }
        else {
            return redirect()->back();
        }
    }

    public function addmelding(Request $request){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            return view('addmelding', compact('account', 'user', 'return'));
        }
        else {
            return redirect()->back();
        }
    }

    public function newmelding(Request $request) {
        $request->validate([
            'start_date'=>'required',
            'end_date'=>'required',
            'message'=>'required'
        ]);
        $bericht = Notifications::create($request);
        if($bericht){
            return back()->with('success','Bericht opgeslagen');
        }else{
            return back()->with('success', 'Er ging iets fout');
        }
    }

    public function editmelding(Request $request){
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $info = Notifications::where('id', '=', $request->id)->first();
            return view('editmelding', compact('account', 'user', 'return', 'info'));
        }
        else {
            return redirect()->back();
        }
    }

    public function updatemelding(Request $request) {
        $id = $request->id;
        $request->validate([
            'start_date'=>'required',
            'end_date'=>'required',
            'message'=>'required'
        ]);
        $update = Notifications::where('id', $id)->update($request->except('id', '_token'));
        if ($update) {
            return redirect('meldingen')->with('success', 'Bericht bewerkt');
        }
        else {
            return back()->with('success', 'Er ging iets fout');
        }
    }

    public function deletemelding(Request $request) {
        if(Auth::check()) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $meldingen = Notifications::paginate(4);
            return view('deletemelding', compact('account', 'user', 'return', 'meldingen'));
        }
        else {
            return redirect()->back();
        }
    }

    public function meldingdelete(Request $request){
        if(Auth::check()) {
            $delete = Notifications::where('id', $request->id)->delete();
            if ($delete){
                return redirect('meldingen')->with('success', 'Melding verwijderd');
            }
            else {
                return back()->with('success', 'Er ging iets fout');
            }
        }
        else {
            return redirect()->back();
        }
    }
}
