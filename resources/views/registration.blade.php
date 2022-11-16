<html>
<head>
    <title>Registratie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
  </head>
</head>
<body>
    @include('menu')
        <style>
        .login {text-decoration:underline !important;}
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="margin-top:20px;">
                <h4>Registratie</h4>
                <hr>
                <form action="{{route('register-user')}}" method="post">
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('failed'))
                <div class="alert alert-danger">{{Session::get('failed')}}</div>
                @endif
                @csrf

                    <div class="form-group">
                        <label for="naam">Naam *</label>
                        <input type="text" class="form-control" placeholder="Naam" name="naam" value="{{old('naam')}}">
                        <span class="text-danger">@error('naam') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="tussenvoegsels">Tussenvoegsels</label>
                        <input type="text" class="form-control" placeholder="Tussenvoegsels" name="tussenvoegsels" value="{{old('tussenvoegsels')}}">
                        <span class="text-danger">@error('tussenvoegsels') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="achternaam">Achternaam *</label>
                        <input type="text" class="form-control" placeholder="Achternaam" name="achternaam" value="{{old('achternaam')}}">
                        <span class="text-danger">@error('achternaam') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="adres">Adres *</label>
                        <input type="text" class="form-control" placeholder="Adres" name="adres" value="{{old('adres')}}">
                        <span class="text-danger">@error('adres') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="postcode">Postcode *</label>
                        <input type="text" class="form-control" placeholder="Postcode" name="postcode" value="{{old('postcode')}}">
                        <span class="text-danger">@error('postcode') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="plaats">Plaats *</label>
                        <input type="text" class="form-control" placeholder="Plaats" name="plaats" value="{{old('plaats')}}">
                        <span class="text-danger">@error('plaats') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email')}}">
                        <span class="text-danger">@error('email') {{$message}} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" value="">
                        <span class="text-danger">@error('password') {{$message}} @enderror</span>
                    </div>

                    <br>
                    <div class="form-group">
                        <button class="btn btn-block btn-primary" type="submit">Registreren</button>
                    </div>
                    <br>

                    <a href="./login">Already user? Login here</a>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>
