<html>
<head>
    <title>Bibliotheek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
  </head>
</head>
<body>
    @include('menu')
        <style>
        .beheer {text-decoration:underline !important;}
    </style>
    <div class="links">
        <a href='/beheer' class="link">Alle gebruikers</a>
        <a href='/newgebruiker' class="link">Nieuwe gebruiker</a>
        <a href='/editgebruiker' class="link" style='text-decoration:underline'>Edit gebruiker</a>
        <a href='/deletegebruiker' class="link">Delete gebruiker</a>
        <a href='/abbonementen' class="link">Abbonementen</a>
        <a href='/meldingen' class="link">Meldingen</a>
    </div><br><br>

    <div class="beheerfield">

        <div class="beheerterug">
            <a href="../editgebruiker"><- Terug</a>
        </div>

        @if ($info->foto)
        <div class="userpicture">
            <img src='{{ asset('storage/images/' .$info->id .'/'. $info->foto) }}' class="picture">
        </div>
        @else
        <div class="userpicture">
            <img src='{{ asset('storage/images/user.png') }}' class="picture">
        </div>
        @endif

        <div class="userinfo">
        <form action='../edit' method='post'>
            @csrf
            <input type="hidden" name='id' value='{{$info->id}}'>
            Voornaam: <input type="text" name="name" class="editform" value="{{ $info->name }}"><br>
            Tussenvoegsels: <input type="text" name="middlename" class="editform" value="{{ $info->middlename }}"><br>
            Achternaam: <input type="text" name="surname" class="editform" value="{{ $info->surname }}"><br>
            E-mail: <input type="text" name="email" class="editform" value="{{ $info->email }}"><br>
            Adres: <input type="text" name="adres" class="editform" value="{{ $info->adres }}"><br>
            Postcode: <input type="text" name="postcode" class="editform" value="{{ $info->postcode }}"><br>
            Plaats: <input type="text" name="city" class="editform" value="{{ $info->city }}"><br>
            Rol: 
            <select class="editform" name="rol_id">
            @foreach ($rollen as $rol) 
                @if ($rol->id==$info->rol_id)
                    <option value="{{ $rol->id }}"selected>{{ $rol->name }}</option><br>
                @else 
                    <option value="{{ $rol->id }}">{{ $rol->name }}</option><br>
                @endif
            @endforeach
            </select><br>

            @if ($info->date_employed)
                Datum van dienst: <input type="text" name="date_employed" class="editform" value="{{ $info->date_employed }}">
            @endif
            @if ($info->subscription_id)
                Abbonement:
                <select class="editform" name="subscription_id">
                @foreach ($abbonementen as $abbonement) 
                    @if ($abbonement->id==$info->subscription_id)
                        <option value="{{ $abbonement->id }}"selected>{{ $abbonement->name }}</option><br>
                    @else 
                        <option value="{{ $abbonement->id }}">{{ $abbonement->name }}</option><br>
                    @endif
                @endforeach
                </select>
            @endif
            <br><input type="submit" value="Opslaan" class='deletesubmit'>
        </form>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
