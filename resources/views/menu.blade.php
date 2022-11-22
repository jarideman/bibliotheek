<div class="test">
    <a href="/" class="home">Home</a>
    <a href="/boeken" class="boek">Alle boeken</a>
    <a href="/login" class="login">Inloggen</a>
    <a href="/account" class="account">Account</a>
    <a href="/terugbrengen" class="return">Boek terugbrengen</a>
    <a href="/beheer" class="beheer">Beheer</a>
    <a href="/logout" class="logout">Uitloggen</a>
</div>
    @if(Auth::check())
        @if ($account == true) 
            <style>.account {display:revert !important;}</style>
        @endif
        @if ($user == true)
            <style>.beheer {display:revert !important;}</style>
        @endif
        @if ($return == true)
            <style>.return {display:revert !important;}</style>
        @endif
        <style>.login {display:none;}</style>
    @else 
        <style>.logout {display:none;}</style>
    @endif
    @if(Session::has('success'))
        <style>
            .overlay {visibility:visible !important; opacity: 1;}
        </style>
        <div id="popup1" class="overlay">
	        <div class="popup">
		        <h2>{{Session::get('success')}}</h2>
		    <a class="close" href="">&times;</a>
	        </div>
        </div>
    @endif

