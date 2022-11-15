<div class="test">
    <a href="/" class="home">Home</a>
    <a href="/" class="boek">Alle boeken</a>
    <a href="/" class="zoek">Zoeken</a>
    <a href="/login" class="login">Inloggen</a>
    <a href="/logout" class="logout">Uitloggen</a>
    <a href="/" class="account">Account</a>
    <a href="/" class="gebruiker">Gebruikers</a>
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

