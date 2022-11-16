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
                    <table class="table">
                    <thead>
                        <th>Titel</th>
                        <th>ISBN</th>
                        <th>Schrijver</th>
                        <th>Genre</th>
                    </thead>
                    <tbody>
                    @foreach ($boeken as $data)
                        <tr onclick='location.href="boek/{{$data->id}}"' >
                            <td>{{$data->title}}</td>
                            <td>{{$data->isbn}}</td>
                            <td>{{$data->writer}}</td>
                            <td>{{$data->genre}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
