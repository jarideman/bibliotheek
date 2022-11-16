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
        .home {text-decoration:underline !important;}
    </style>

<div class="rightbar">
    <div class="meldingen">
        <b>Meldingen</b><br>
        @foreach ($meldingen as $meldingen)
            {{$meldingen->start_date}}:
            {{$meldingen->message}}
        @endforeach
    </div>

    <div class="newbooks">
        <b style="padding-bottom:20px;">Nieuwste boeken</b><br>
        <div style="display:none; padding-top:20px;">{{$i=1}}</div>
        @foreach ($newBooks as $newBooks)
            {{$i++}}.
            <a href="boek/{{$newBooks->id}}">{{$newBooks->title}}</a>
            <br>
        @endforeach
    </div>
</div>


<div class="text">
    De bieb is een plek waar jij jezelf kunt ontwikkelen en
    waar iedereen welkom is. Lezen, leren, ontmoeten óf 
    op zoek naar inspiratie en antwoorden. Het kan en mag allemaal.
    En als lid kun je nóg meer!
</div> 
<img src='{{ asset('storage/images/foto.png') }}' class="foto">
<a href="registration">
<div class="lid">
    Wordt lid
</div></a>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
