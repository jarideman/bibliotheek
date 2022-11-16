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
        tr:hover {cursor: pointer;}
    </style>
        <div class="boekenlijst">
        <table class="table">
            Alle boeken
            <div class="filters">
                <form action="" id="sort" methode="get">
                    <input type="text" class="form-control" placeholder="Schrijver" name="schrijver">
                    <input type="text" class="form-control" placeholder="ISBN" name="isbn">
                    <input type="text" class="form-control" placeholder="Titel" name="titel">
                    <select class="form-select" name="genre">
                        <option value="" disabled selected>Genre</option>
                        @foreach ($genre as $genre)
                            <option value="{{$genre}}">{{$genre}}</option>
                        @endforeach
                    </select>
                    <input type="submit" value="Zoeken">
                </form>
            </div>
            @foreach ($boeken as $data)
                <tr onclick='location.href="boek/{{$data->id}}"' class="boeken">
                    <td>
                        <div class="fotoboek">
                        @if ($data->foto)
                            <img src="{{$data->foto}}" class="boekfoto">
                        @else
                            <img src='{{ asset('storage/images/boek.png') }}' class="boekfoto">
                        @endif
                        </div>
                        <div class="textboek">
                        Titel: {{$data->title}}<br>
                        ISBN: {{$data->isbn}}<br>
                        Schrijver: {{$data->writer}}<br>
                        Genre: {{$data->genre}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
