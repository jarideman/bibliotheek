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
        .return {text-decoration:underline !important;}
    </style>

   

    <div class="returnform">
        <h2 style="color:black">Boek terug brengen</h2>
        <form action='' method="get" id="myForm" class="reserverenform">
            <input type="text" placeholder="Klant id" value='@if (isset($name)){{$name}}@endif' name="user_id"><br>
            @if (isset($name))
                @if (isset($boeken))
                    @if ($boeken == '[]')
                        Geen geleende boeken<br>
                    @else
                        @foreach ($boeken as $boeken)
                            <input type="checkbox" value='{{$boeken->book->id}}' name='boek[]'> {{$boeken->book->title}}</input><br>
                        @endforeach
                        <style>.submit {display:none;}</style>
                        <input type="submit" value="Terug brengen" class="returnbook">
                    @endif
                @else
                    Geen geleende boeken
                @endif
            @endif
            <input type="submit" value="Zoek" class="submit">
        </form>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
