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
        <a href='/deletegebruiker' class="link">Delete gebruiker</a>
        <a href='/abbonementen' class="link">Abbonementen</a>
        <a href='/meldingen' class="link" style='text-decoration:underline'>Meldingen</a>
    </div><br><br>

    <div class="beheerfield">

    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
