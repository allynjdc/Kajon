<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('images/kajon.ico') }}" type="image/x-icon">

    <title>Kajon</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize.min.css')}} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize_icons.css')}} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/main.css')}} " rel="stylesheet" type="text/css">

    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <!-- Styles -->

    <style type="text/css">

        body{
            margin: 0;
            background-color: #fffcff;
        }

        .full-height{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 90vh!important;
        }
        .kajon-logo{
            max-width: 7.5em;
        }
        .padding-12{
            padding: 0 12.5%;
        }
        .center{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .web-name{
            letter-spacing: 0.75em;
            font-size: 2em;
            color: #444;
            transform: translate(0.4em, 0);
            margin-bottom: 0.25em;
        }
        .aklan{
            font-size: 1em;
            letter-spacing: 0.7em;
            color: #888;
            margin: 0 0 3em 0;
            transform: translate(0.4em, 0);
        }
        input[type="text"],
        input[type="password"]{
            box-sizing: border-box;
            padding: 0.25em 1em;
            text-align: center;
        }

        input:focus{
            border-color: #00b0ff!important;
            box-shadow: 0 1px 0 #00b0ff!important;
        }

        input:focus + label{
            color: #00b0ff!important;
            font-size: 1em!important;
        }

        label .material-icons.left{
            margin-right: 0.1em!important;
            transform: translate(0, -2px);
        }

        label{
            /* text-align: center!important; */
            left: 50%!important;
            transform: translate(-50%, 0);
            display: flex;
        }

        label.active{
            transform: translate(-50%, -2em)!important;
        }

    </style>
</head>
<body>
<div class="row">
    <div class="col s10 offset-s1 m4 offset-m4 full-height">
        <img src="{{ asset('images/kajon.png') }}" alt="logo" class="kajon-logo" />
        <h3 class="web-name">KAJON</h3>
        <h4 class="aklan">OPES AKLAN</h4>
        <form class="padding-12 center" role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="input-field {{ $errors->has('username') ? ' has-error' : '' }}">
                <input type="text" name="username" id="username" value="{{ old('email') }}" required autofocus />
                <label for="username"><i class="material-icons left">account_circle</i> Username</label>
            </div>

            @if ($errors->has('username'))
                <span class="error-message">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif

            <div class="input-field">
                <input type="password" name="password" id="password" required />
                <label for="password"><i class="material-icons left">lock</i> Password</label>
            </div>

            @if ($errors->has('password'))
                <span class="error-message">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            <button type="submit" class="waves-effect waves-light btn light-blue lighten-1">Login</button>
            <!-- <input type="submit" name="login"> -->
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/materialize.min.js') }}"></script>
<script type="text/javascript">
    $(".has-error").click(function(){
        $(this).removeClass("has-error");
        $(this).next().addClass("resolved");
    });
</script>
</body>
</html>
