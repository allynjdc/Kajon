@extends("layout.navigation")

@section('content') 

    <div class="row">
        <!-- <div class="col s4"></div> -->
        <div class="col s12 m4 offset-m4">

            @if (Auth::user()->active == '0')
                <h1 class="greetings">Welcome to KAJON!</h1>
                <img src="{{asset('images/kajon.png')}}" class="kajon" alt="kajon logo">
                <h2 class="instruction">Before you can proceed, you must change your password.</h2>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <h2 class="header">Edit Profile</h2>
            @endif

            <form role="form" method="GET" action="/edited" class="password-form">
                {{ csrf_field() }}
                {{method_field('PUT')}}
                <input type="hidden" name="id" value="{{ Auth::user()->id }}">    
                <div class="input-field">
                    <input type="password" id="password" name="password">
                    <label for="password"><i class="material-icons">lock_outline</i> Current Password</label>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif 
                </div>

                <div class="input-field">
                    <input type="password" id="new_password" name="new_password">
                    <label for="new_password"><i class="material-icons">lock_outline</i> Password</label>

                </div>
                <div class="input-field">
                    <input type="password" id="conf-password" name="conf_password">
                    <label for="conf_password"><i class="material-icons">lock_open</i> Confirm Password</label>

                </div>
                <div class="input-field submit">
                    <button class="btn waves-effect waves-light blue darken-1" type="submit">
                        Change password
                        <i class="right material-icons">send</i>
                    </button>
                </div>
            </form>

            @if (Auth::user()->active == '0')
                <a href="{{ route('logout') }}" class="logout btn waves-effect waves-light red" 
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                   <i class="material-icons left">power_settings_new</i> Logout
                </a>
            @endif
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">
    <style type="text/css">
        
        .header{
            font-size: 1.5em;
            color: #444;
            text-transform: uppercase;
            margin-bottom: 1.75em;
        }

        .submit{
            display: flex;
            justify-content: center;
        }

    </style>

    @if (Auth::user()->active == '0')
    <style type="text/css">
        
        .navbar-fixed{
            display: none;
        }

        .greetings{
            margin-top: 0;
            font-size: 1.5em;
            text-align: center;
        }

        .kajon{
            max-width: 100%;
            width: 5em;
            margin: 0 auto;
            display: block;
        }

        .instruction{
            color: #888;
            font-size: 1.1em;
            text-align: center;
            margin-bottom: 2.5em;
        }

        .password-form{
            width: 80%;
            margin: 0 auto;
        }

        .logout{
            margin: 2em 0 0 0;
            text-transform: uppercase;
            width: 80%;
        }

        .row > .col:first-child{
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 1em 0;
        }

        @media screen and (min-width: 600px) {
            .logout{
                position: absolute;
                top: -1em;
                right: 1em;
                color: #d23737;
                background-color: transparent!important;
                box-shadow: none;
                width: unset;
                /* padding: 1em; */
            }

            .logout:hover{
                box-shadow: none;
            }
        }

    </style>
    @endif
    
@endpush

@push('scripts')

    @if (Auth::user()->active == '0')
        <script type="text/javascript">
            
        </script>
    @endif

@endpush