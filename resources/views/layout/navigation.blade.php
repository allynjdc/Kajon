 <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('images/kajon.ico') }}" type="image/x-icon">


    <title>Kajon</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize_icons.css')}} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize.min.css')}} " rel="stylesheet" type="text/css">

    <!-- Styles -->

    <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/main2.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/navigation.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')

</head>
<body>
    <div class="navbar-fixed">
        <nav id="navigation" class="light-blue lighten-1">
            <div class="nav-wapper">
                <a href="/home" class="brand-logo center hide-on-med-and-up"><img src="{{asset('images/kajon.png')}}"></a>
                <a href="/home" class="brand-logo tablet center"><img src="{{asset('images/kajon.png')}}"> KAJON</a>
                @if(Auth::user()->adminRole() != 2 OR is_null(Auth::user()->adminRole()))
                <a class="btn btn-square light-green waves-effect upload-btn mobile hide750" href="#upload"><i class="material-icons">note_add</i></a>
                @endif
                <ul class="left hide-on-med-and-down search-bar">
                    <li><a href="/home" class="brand-logo"><img src="{{asset('images/kajon.png')}}"> KAJON</a></li>
                    <li>
                        <form id="search-form" action="/home/allfiles" method="GET">
                            <div class="input-field">
                                <input id="search" name="search" type="search" placeholder="Search..." required>
                                <!-- <label class="label-icon" for="search"></label> -->
                            </div>
                            <button type="submit" class="btn waves-effect waves-light blue lighten-1"><i class="material-icons">search</i></button>
                        </form>
                    </li>
                </ul>
                <ul class="left nav-mobile">
                    <li><a href="#" data-activates="side-navigation" class="button-collapse"><i class="material-icons">menu</i></a></li>
                </ul>
                <ul class="right hide-on-med-and-down">
                    <li class="icon-link"><a href="/home" class="active waves-effect tooltipped" data-position="bottom" data-delay="50" data-tooltip="Home"><i class="material-icons">home</i></a></li>
                    <li class="icon-link"><a href="/reminders" class="waves-effect tooltipped" data-positio="bottom" data-delay="50" data-tooltip="Notices"><i class="material-icons">event_note</i>
                    @if(!(Auth::user()->isAdmin()) AND count(Auth::user()->usedTags())) 
                        <span class="badge red pulse lighten-1 white-text">
                            {{ count(Auth::user()->usedTags()) }} 
                        </span>
                    @endif</a></li>
                    <!-- <li><a href="/home">Home</a></li> -->
                    <!-- <li><a href="/reminders">Reminders</a></li> -->
                    <li><a class="waves-effect dropdown-button" href="#options" data-activates="options"><img class="nav-img" onerror="this.src='images/default-profile-image.jpg';" src="{{ asset(Auth::user()->profile_picture) }}"> {{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a></li>
                </ul>
                <ul class="side-nav" id="side-navigation">
                    <li><a class="waves-effect waves-light profile-link" href="/profile"><img class="nav-img" onerror="this.src='images/default-profile-image.jpg';" src="{{ asset(Auth::user()->profile_picture) }}"> {{ Auth::user()->name }}</a></li>
                    <li>
                        <form id="search-form-side" action="/home/allfiles" method="GET">
                            <div class="input-field">
                                <input id="search-side" name="search" type="search" placeholder="Search..." required />
                            </div>
                            <button type="submit" class="btn waves-effect waves-light"><i class="material-icons">search</i></button>
                        </form>
                    </li>
                    <li><a href="/home" class="active"><i class="material-icons">home</i> Home</a></li>
                    <li><a href="/reminders"><i class="material-icons">event_note</i> Reminders</a></li>
                    <li><a href="/edit_profile"><i class="material-icons">settings</i> Account Settings</a></li>
                    <li><a href="/leaderboard"><i class="material-icons">assessment</i> Leaderboard</a></li>
                    @if(Auth::user()->isAdmin())
                    <li><a href="/user/create"><i class="material-icons">person_add</i> Create User</a></li>
                    @if(Auth::user()->adminRole() == 0)
                    <li><a href="/password_reset"><i class="material-icons">lock</i> Password Reset</a></li>
                    @endif
                    @endif
                    <li><a class="waves-effect" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                           <i class="material-icons">power_settings_new</i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <ul id="options" class="dropdown-content">
            <li><a href="/profile" class="active"><i class="material-icons">person</i> Profile</a></li>
            <li><a href="/edit_profile"><i class="material-icons">settings</i> Account Settings</a></li>
            <li><a href="/leaderboard"><i class="material-icons">assessment</i> Leaderboard</a></li>
            @if(Auth::user()->isAdmin() )
            <li><a href="/user/create"><i class="material-icons">person_add</i> Create User</a></li>
            @if(Auth::user()->adminRole() == 0)
            <li><a href="/password_reset"><i class="material-icons">lock</i> Password Reset</a></li>
            @endif
            @endif
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                   <i class="material-icons">power_settings_new</i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>

    @yield('content')

    <script type="text/javascript" src="{{ asset('js/js.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/view-file.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/changeDate.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/icon_color_change.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".button-collapse").sideNav();
            $('.modal').modal();
            $('select').material_select();
            $(document).ready(function(){
                $('.tooltipped').tooltip({delay: 0});
            });
        })
    </script>

    @stack('scripts')

</body>
</html>
