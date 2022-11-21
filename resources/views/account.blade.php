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
        .account {text-decoration:underline !important;}
    </style>

    <div class="accountinfo">
        Abbonement:
        @if ($abbonement)
            {{$abbonement->name}} (<a href="abbonementwijzigen">wijzigen</a>)<br>
        @else
            <a href="abbonementwijzigen">afsluiten</a><br><br>
        @endif
        @if ($info->foto)
        <div class="profilepicture">
            <img src='{{ asset('storage/images/' .$info->id .'/'. $info->foto) }}' class="picture">
        </div>
        @else
        <div class="profilepicture">
            <img src='{{ asset('storage/images/user.png') }}' class="picture">
        </div>
        @endif
        <form action="{{route('fileUpload')}}" method="post" enctype="multipart/form-data">
            @csrf 
                <input type="hidden" name="id" value='{{$info->id}}'>
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="chooseFile">
                </div>
                <button type="submit" name="submit" class="uploadfoto">
                    Upload Foto
                </button>
        </form>
        Klant id: {{$info->id}}<br>
        {{$info->name}}        
        {{$info->middlename}}
        {{$info->surname}}<br>
        {{$info->adres}}<br>
        {{$info->city}}<br>
    </div>


    <div class="accountboeken">
        <table class="tableaccountboeken">

    @if (request()->type == 'reservations')
            <a href="account?type=lentbooks">Gereserveerde boeken<img src='{{ asset('storage/images/switch.png') }}' class="switch"></a>
        <thead>
            <th>Boek</th>
            <th>Reserverings datum</th>
            <th>Status</th>
            <th>Cancel reservatie</th>
        </thead>
        <tbody>
        @foreach ($reservations as $reservation)
        <tr>
            
            <td>{{$reservation->book->title}}</td>
            <td>{{$reservation->reservation_date}}</td>
            @php $test = $status->where('book_id', '=', $reservation->book->id)@endphp
            @if ($test == '[]')
                <td>Beschikbaar</td>
            @else
                <td>Uitgeleend</td>
            @endif
            <td><a href="cancel/{{$reservation->book->id}}" style="text-align:left !important;">Cancel reservatie</a></td>
        </tr>
        @endforeach
    @else
        <a href="account?type=reservations">Geleende boeken<img src='{{ asset('storage/images/switch.png') }}' class="switch"></a>
        <thead>
            <th>Boek</th>
            <th>Uitleen datum</th>
            <th>Retour datum</th>
            <th>Verlengen</th>
        </thead>
        <tbody>
        @foreach ($lent_books as $lent_book)
        <tr>
            <td>{{$lent_book->book->title}}</td>
            <td>{{$lent_book->lent_date}}</td>
            <td>{{$lent_book->return_date}}</td>
            @if ($lent_book->times_extended == 0)
            <td><a href="verlengen/{{$lent_book->book->id}}" style="text-align:left !important">Verlengen</a></td>
            @else
                <td>Al verlengd</td>
            @endif
        </tr>
        @endforeach
        </tbody>
    @endif
    </table>
    @if (request()->type == 'reservations')
        {{ $reservations->links('pagination') }}
    @else
        {{ $lent_books->links('pagination') }}
    @endif
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
