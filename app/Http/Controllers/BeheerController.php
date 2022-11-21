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

class BeheerController extends Controller
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

    public function beheer(){
        if(Session()->has('loginId')) {
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

    public function newgebruiker(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::all();
            return view('newgebruiker', compact('account', 'user', 'return', 'gebruikers'));
        }
        else {
            return redirect()->back();
        }
    }

    public function editgebruiker(){
        if(Session()->has('loginId')) {
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

    public function deletegebruiker(){
        if(Session()->has('loginId')) {
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

    public function abbonement(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::all();
            return view('abbonement', compact('account', 'user', 'return', 'gebruikers'));
        }
        else {
            return redirect()->back();
        }
    }

    public function meldingen(){
        if(Session()->has('loginId')) {
            $account = $this->CheckRol('view_account');
            $user = $this->CheckRol('admin');
            $return = $this->CheckRol('return_book');
            $gebruikers = User::all();
            return view('meldingen', compact('account', 'user', 'return', 'gebruikers'));
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

    public function deleteuser(Request $request){
        $account = $this->CheckRol('view_account');
        $user = $this->CheckRol('admin');
        $return = $this->CheckRol('return_book');
        $info = User::where('id', '=', $request->id)->first();
        if ($info->subscription_id) {
            $abbonement = Subscription::where('id', '=', $info->subscription_id)->first();
            return view('deleteuser', compact('account', 'user', 'return', 'info', 'abbonement', s));
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
}
