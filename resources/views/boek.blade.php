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
        <a href="../boeken"><- Terug</a>
    </div>


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
                        @if (!$uitgeleend)
                            <button class="reserverenbutton" onclick="location.href='/boek/reserveren/{{$info->id}}'">Reserveren</button>
                        @endif
                    @endif
                    @if ($reserverenKlant == true)
                        @if ($status == 'Uitgeleend')
                        <button type="button" class="reserverenbutton">Reserveren</button>
                        <div class="content">
                        <form action='reserverenklant' method="POST" class="reserverenform">
                                @csrf
                                <input type="hidden" value="{{$info->id}}" name="book_id" required>
                                <input type="text" placeholder="Klant id" name="klant_id" required><br>
                                <input type="submit" value="Reserveren">
                        </form>
                        </div>
                        @endif
                    @endif
                @endif
            </div>

            <div class="uitlenen">
            @if ($status == 'Beschikbaar' or $status == 'Gereserveerd')
                @if ($uitlenen == true)
                        <button type="button" class="reserverenbutton">Uitlenen</button>
                        <div class="content">
                        <form action='uitlenen' method="POST" class="reserverenform">
                                @csrf
                                <input type="hidden" value="{{$info->id}}" name="book_id" required>
                                <input type="text" placeholder="Klant id" name="klant_id" required><br>
                                <input type="submit" value="Uitlenen">
                        </form>
                        </div>
                @endif
            @endif
            </div>
        </div>

<script>
var coll = document.getElementsByClassName("reserverenbutton");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
