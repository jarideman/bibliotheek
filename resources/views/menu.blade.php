<div class="test">
    <a href="/" class="home">Home</a>
    <a href="/boeken" class="boek">Alle boeken</a>
    <a href="/login" class="login">Inloggen</a>
    <a href="/account" class="account">Account</a>
    <a href="/gebruikers" class="gebruiker">Gebruikers</a>
    <a href="/logout" class="logout">Uitloggen</a>
</div>
    @if(Session::has('loginId')) 
        @if ($account == true) 
            <style>.account {display:revert !important;}</style>
        @endif
        @if ($user == true)
            <style>.gebruiker {display:revert !important;}</style>
        @endif
        <style>.login {display:none;}</style>
    @else 
        <style>.logout {display:none;}</style>
    @endif

