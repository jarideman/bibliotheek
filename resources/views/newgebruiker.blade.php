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
        <a href='/newgebruiker' class="link" style='text-decoration:underline'>Nieuwe gebruiker</a>
        <a href='/editgebruiker' class="link">Edit gebruiker</a>
        <a href='/deletegebruiker' class="link">Delete gebruiker</a>
        <a href='/abbonementen' class="link">Abbonementen</a>
        <a href='/meldingen' class="link">Meldingen</a>
    </div><br><br>

    <div class="beheerfield">
        <form action="../new" method="post" enctype="multipart/form-data">
            @csrf
            Voornaam: <input type="text" name="name" class="editform" required><br>
            Tussenvoegsels: <input type="text" name="middlename" class="editform" ><br>
            Achternaam: <input type="text" name="surname" class="editform" required><br>
            E-mail: <input type="text" name="email" class="editform" required><br>
            Wachtwoord: <input type="password" name="password" class="editform" required><br>
            Adres: <input type="text" name="adres" class="editform" required><br>
            Postcode: <input type="text" name="postcode" class="editform" required><br>
            Plaats: <input type="text" name="city" class="editform" required><br>
            Rol: 
            <select class="editform" name="rol_id" id='rol_id' onChange="handleSelection(value)" required> 
            <option disabled selected value>--Selecteer rol--</option>
            @foreach ($rollen as $rol)                     
                    <option value="{{ $rol->id }}">{{ $rol->name }}</option>
            @endforeach
            </select><br>
            <div id="date"></div>
                <div class="bestand">
                    <h6>Profielfoto:</h6>
                    <input type="file" name="file" class="custom-file-input" id="chooseFile">
                </div><br>
                <button type="submit" class="uploadfoto">Gebruiker opslaan</button>
    </div>

<script type="text/javascript">

function handleSelection(choice) {
if (!(choice == "2")) {
    document.getElementById('date').innerHTML = 'Datum van dienst: <input type="text" name="date_employed" class="editform" required>';
}
else{
    document.getElementById('date').innerHTML = '';
}}
</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
