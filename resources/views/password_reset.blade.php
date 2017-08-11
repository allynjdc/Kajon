@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12 m6 offset-m3">
            <form id="reset-form" action="/reset">
                <h1>Reset password</h1>
                <input type="text" id="name-field" name="user" placeholder="Who's password do you want to change?">
                <ul id="suggested-users">
                    @foreach($users as $user)
                    <li>

                    	<img src="{{asset($user->profile_picture)}}" alt="user's photo" onerror="this.src='images/default-profile-image.jpg';">
                    	<span class="name">{{$user->name}}</span>
                    	<span class="id">{{$user->id}}</span>
                    </li>
<!--                     <li>
                    	<img src="{{asset('images/lincy.png')}}" alt="user's photo">
                    	<span class="name">Lincy Legada</span>
                    	<span class="id">3</span>
                    </li> -->
                    @endforeach
                </ul>
                <div id="picked-user">
                    <img src="{{asset('images/clyde.png')}}" alt="user's photo">
                    <h3 class="name">Clyde Joshua Delgado</h3>
                </div>
                  <div class="flash-message">
                        @foreach(['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-'.$msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </p>
                            @endif
                        @endforeach
                    </div>
                    @if ($errors->has('image'))
                    <div class="flash-message">
                        <p class="alert alert-danger">
                            {{ $errors->first('image') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        </p>
                    </div>
                    @endif
                <div id="pass-input-field-div">
                    <h3>Enter code to complete the reset.</h3>
                    <input type="hidden" name="user_id" id="user-id">
                    <input type="password" name="confirm_pass" id="confirm_pass" placeholder="PASSWORD">
                    <button type="submit" id="reset-btn" class="btn btn-block right waves-effect waves-light blue lighten-1"><i class="material-icons left">refresh</i> Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/password_reset.css') }}" /> -->
    <style type="text/css">

        .col{
            display: flex;
            align-items: center;
            min-height: calc(100vh - 64px);
            flex-direction: column;
            justify-content: center;
        }

        #reset-form{
            position: relative;
        }

        @media screen and (min-width: 900px) {
            #reset-form{
                width: 60%;
            }
        }

        #reset-form > h1:first-child{
            font-size: 2.25em;
            text-transform: uppercase;
            text-align: center;
        }

        .id{
        	display: none;
        }

        #name-field{
            border: none;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
            padding: 1em;
            height: unset;
            line-height: unset;
            box-sizing: border-box;
            margin-bottom: 0;
        }

        #suggested-users{
            background-color: white;
            width: 100%;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
            margin-top: 0;
            position: absolute;
            display: none;
            z-index: 10;
        }

        #suggested-users > li{
			padding: 0.25rem;
			display: flex;
			/* justify-content: center; */
			align-items: center;
        }

        #suggested-users img{
            height: 2.5rem;
            width: 2.5rem;
            margin-right: 0.25rem;
        }

        #picked-user{
        	margin-top: 1rem;
        	display: flex;
        	justify-content: center;
        	flex-direction: column;
        	background-color: white;
        	box-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
        	padding: 1rem;
        	position: relative;
 			display: none;
        }

        #picked-user .clear{
        	position: absolute;
        	top: 1rem;
        	right: 1rem;
        	color: red;
        	opacity: 0.5;
        }

        #picked-user .name{
        	font-size: 1.5em;
        	text-align: center;
        	margin-bottom: 0;
        }

        #picked-user img{
            height: 10rem;
            width: 10rem;
            margin: 0 auto;
            border-radius: 3px;
            display: block;
        }

		#pass-input-field-div{
			display: none;
		}

        #pass-input-field-div > h3:first-child{
        	font-size: 1.5rem;
        	text-align: center;
        }

        #confirm_pass{
        	text-align: center;
        	border: 2px dashed #AAA;
        	border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
            width: 100%;
            box-sizing: border-box;
            display: block!important;
        }

		/* #reset-btn{
			display: none;
		} */

    </style>
@endpush

@push('scripts')
<meta name="_token" content="{{ csrf_token() }}" />
<script type="text/javascript">
	$(document).ready(function(){
		$("#reset-btn").hide();
		$("#name-field").on("input", suggest);
		$("#name-field").on("focus", suggest);
		$("#name-field").on("blur", function(){close("#suggested-users")});
		$("#suggested-users > li").on("click", selectUser);
		$("#confirm_pass").on("input", showBtn);
		// console.log("Password reset js loaded.");
	});

	function open(id){$(id).delay(100).slideDown(100); console.log("Opening");}
	function close(id){$(id).delay(100).slideUp(100); console.log("Closing");}

	function suggest(){
		var value = $("#name-field").val();

		var employees = $("#suggested-users > li > .name");
		var regex = new RegExp(value, 'i');

		if (value) {
			for (var i = 0; i < employees.length; i++) {
				if (employees.eq(i).html().match(regex)) {
					employees.eq(i).parent().css("display", "flex");
				} else {
					employees.eq(i).parent().hide();
				}
			}
			open("#suggested-users");
		} 
		else {
			close("#suggested-users");
		}
	}

	function selectUser(){
		// console.log($(this));

		var name = $(this).find(".name").eq(0).html();
		var src = $(this).find("img").eq(0).attr("src");
		var id = $(this).find(".id").eq(0).html();

		// console.log(name);
		// console.log(src);
		// console.log(id);

		$("#picked-user").find(".name").eq(0).html(name);
		$("#picked-user").find("img").eq(0).attr("src", src);
		$("#user-id").val(id);

		// console.log($("#user-id").val());

		$("#picked-user").slideDown();
		$("#pass-input-field-div").slideDown();

		close("#suggested-users");
	}

	function showBtn(){
		var value = $("#confirm_pass").val();

		if (value.length >= 3) {
			$("#reset-btn").fadeIn(300);
			// console.log("Enough.");
		} else {
			$("#reset-btn").fadeOut(300);
			// console.log("Too short");
		}
	}


</script>
@endpush
