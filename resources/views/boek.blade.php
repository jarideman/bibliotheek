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
        .boek {text-decoration:underline !important;}
    </style>
    <div class="terug">
        <a href="{{url()->previous()}}"><- Terug</a>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
    @endif
    @if(Session::has('failed'))
        <div class="alert alert-danger">{{Session::get('failed')}}</div>
    @endif
    <div class="fotobook">
        @if ($info->foto)
            <img src="{{$info->foto}}" class="bookfoto">
        @else
            <img src='{{ asset('storage/images/boek.png') }}' class="bookfoto">
        @endif
    </div>
        <div class="boekinfo">
            Titel: {{$info->title}}<br>
            ISBN: {{$info->isbn}}<br>
            Schrijver: {{$info->writer}}<br>
            Genre: {{$info->genre}}<br>
            Status: {{$status}}<br>
            Locatie: Hoornbeeckerland
            <div class="reserveren">
                @if ($status == 'Beschikbaar' or $status == 'Uitgeleend')
                    @if ($reserveren == true)    
                        <button class="reserverenbutton" onclick="location.href='reserveren/{{$info->id}}'">Reserveren</button>
                    @endif
                @endif
            </div>
        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
