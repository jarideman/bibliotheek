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
    <div class="abbonementen">
    <div class="count">{{$i=1}}</div>
        @foreach ($abbonementen as $abbonementen)
            <div class="abbonement">
            @if ($abbonementen->id == $abbonement->id)
                <input name="checkbox{{$i}}" id="checkbox{{$i}}" type="checkbox" class="name" checked><label for="checkbox{{$i}}">
                    <div class="abbonementinfo">
                        {{$abbonementen->name}}<br>
                        Prijs van het abbonement: €{{$abbonementen->price}}<br>
                        Max boeken lenen/reserveren: {{$abbonementen->books}}
                    </div>
                </label>
            @else 
                <input name="checkbox{{$i}}" id="checkbox{{$i}}" type="checkbox" class="name"><label for="checkbox{{$i}}">
                    <div class="abbonementinfo">
                        {{$abbonementen->name}}<br>
                        Prijs van het abbonement: €{{$abbonementen->price}}<br>
                        Max boeken lenen/reserveren: {{$abbonementen->books}}
                    </div>
                </label>
            @endif
            <div class="count">{{$i++}}</div>
            </div>
        @endforeach
    </div>




   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script type="text/javascript">
   $(document).ready(function(){

      $('.name').click(function() {
         $('.name').not(this).prop('checked', false);
      });

   });
   </script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
