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
        <a href='/editgebruiker' class="link">Edit gebruiker</a>
        <a href='/deletegebruiker' class="link" style='text-decoration:underline'>Delete gebruiker</a>
        <a href='/abbonementen' class="link">Abbonementen</a>
        <a href='/meldingen' class="link">Meldingen</a>
    </div><br><br>

    <div class="beheerfield">

        <div class="beheerterug">
            <a href="../deletegebruiker"><- Terug</a>
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
            Name: {{$info->name}} {{$info->middlename}} {{$info->surname}}<br>
            E-mail: {{$info->email}}<br>
            Adres: {{$info->adres}}<br>
            Postcode: {{$info->postcode}}<br>
            Plaats: {{$info->city}}<br>
            Rol: {{$info->rol->name}}<br>
            @if ($info->date_employed)
                Datum van dienst: {{$info->date_employed}}
            @endif
            @if ($info->subscription_id)
                Abbonement: {{$abbonement->name}}
            @endif

            <form action='../delete' method='post'>
                @csrf
                <input type="hidden" name='id' value='{{$info->id}}'>
                <input type="submit" value="Verwijderen" class='deletesubmit'>
            </form>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
