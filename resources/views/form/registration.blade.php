@extends("layout.navigation")

@section('content')
    <!-- <div id="modal-success" class="modal">
        <div class="modal-content">
            <h4>Success</h4>
            <p></p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Close</a>
        </div>
    </div> -->

    <!-- <div class="flash-message">
        <p class="alert alert-success"><strong>Account Creation Success!</strong> You have successfully created an account for Clyde Joshua Delgado. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </p>
    </div> -->

    <div class="row">
        <!-- <div class="col s4"></div> -->
        <div class="col s4 offset-s4">
            <div class="flash-message">
                @foreach(['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-'.$msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} </p>
                    @endif
                @endforeach
            </div>
        
            <h2 class="header">Create User</h2>
            <form action="/user" method="POST" role="form">
                {{ csrf_field() }}
                <div class="input-field{{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" />
                    <label for="name"><i class="material-icons">account_circle</i> Name</label>
                </div>

                @if ($errors->has('name'))
                    <span class="error-message">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif

                <div class="input-field{{ $errors->has('username') ? ' has-error' : '' }}">
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required />
                    <label for="username"><i class="material-icons">face</i> Username</label>
                </div>

                @if ($errors->has('username'))
                    <span class="error-message">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif

                <div class="input-field{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required />
                    <label for="email"><i class="material-icons">email</i> E-mail</label>
                </div>

                @if ($errors->has('email'))
                    <span class="error-message">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                
                @if(Auth::user()->isAdmin() AND Auth::user()->adminRole() == 0)
                <div class="input-field{{ $errors->has('role') ? ' has-error' : '' }}">
                    <select name="role" id="role">
                        <optgroup label="Users">
                            <option value="1"
                                @if(old('role') == 1)
                                    selected="selected" 
                                @endif
                            >Election Officer or Election Assistant</option>
                        </optgroup>
                        <optgroup label="Admins">
                            <option value="2"
                                @if(old('role') == 2)
                                    selected="selected" 
                                @endif
                            >System Administrator</option>
                            <option value="3"
                                @if(old('role') == 3)
                                    selected="selected" 
                                @endif
                            >File Administrator</option>
                            <option value="4"
                                @if(old('role') == 4)
                                    selected="selected" 
                                @endif
                            >Regular Administrator</option>
                        </optgroup>
                    </select>
                    <label>Account Type</label>
                </div>
                @else
                <div class="input-field{{ $errors->has('role') ? ' has-error' : '' }}">
                    <select name="role" id="role" readonly="readonly">
                        <option value="1" selected="selected">Regular User</option>
                    </select>
                    <label>Account Type</label>
                </div>
                @endif

                @if ($errors->has('role'))
                    <span class="error-message">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span>
                @endif

                <div class="input-field{{ $errors->has('municipality') ? ' has-error' : '' }}" id="municipality-field">
                    <select name="municipality" id="municipality">
                        <option value="1"
                            @if(old('municipality') == 1)
                                selected="selected"
                            @endif
                        >Altavas</option>
                        <option value="2"
                            @if(old('municipality') == 2)
                                selected="selected"
                            @endif>Balete</option>
                        <option value="3"
                            @if(old('municipality') == 3)
                                selected="selected"
                            @endif>Banga</option>
                        <option value="4"
                            @if(old('municipality') == 4)
                                selected="selected"
                            @endif>Batan</option>
                        <option value="5"
                            @if(old('municipality') == 5)
                                selected="selected"
                            @endif>Buruanga</option>
                        <option value="6"
                            @if(old('municipality') == 6)
                                selected="selected"
                            @endif>Ibajay</option>
                        <option value="7"
                            @if(old('municipality') == 7)
                                selected="selected"
                            @endif>Kalibo</option>
                        <option value="8"
                            @if(old('municipality') == 8)
                                selected="selected"
                            @endif>Lezo</option>
                        <option value="9"
                            @if(old('municipality') == 9)
                                selected="selected"
                            @endif>Libacao</option>
                        <option value="10"
                            @if(old('municipality') == 10)
                                selected="selected"
                            @endif>Madalag</option>
                        <option value="11"
                            @if(old('municipality') == 11)
                                selected="selected"
                            @endif>Makato</option>
                        <option value="12"
                            @if(old('municipality') == 12)
                                selected="selected"
                            @endif>Malay</option>
                        <option value="13"
                            @if(old('municipality') == 13)
                                selected="selected"
                            @endif>Malinao</option>
                        <option value="14"
                            @if(old('municipality') == 14)
                                selected="selected"
                            @endif>Nabas</option>
                        <option value="15"
                            @if(old('municipality') == 15)
                                selected="selected"
                            @endif>New Washington</option>
                        <option value="16"
                            @if(old('municipality') == 16)
                                selected="selected"
                            @endif>Numancia</option>
                        <option value="17"
                            @if(old('municipality') == 17)
                                selected="selected"
                            @endif>Tangalan</option>
                    </select>
                    <label for="municipality">Municipality</label>
                </div>

                @if ($errors->has('municipality'))
                    <span class="error-message">
                        <strong>{{ $errors->first('municipality') }}</strong>
                    </span>
                @endif
                <!-- <div> -->
                    <!-- Regular Users can CRUDE in their own account -->
                    <!-- Regular Admin can CRUDE in their own account and can view files on other users -->
                    <!-- File Admin can CRUDE in their own account and upload files on other users -->
                    <!-- Super Admin can CRUDE in their own account and upload and view files on other users -->
                <!-- </div> -->
                <div class="input-field{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" id="password" name="password" required />
                    <label for="password"><i class="material-icons">lock_outline</i> Password</label>
                </div>
                
                @if ($errors->has('password'))
                    <span class="error-message">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif

                <div class="input-field{{ $errors->has('role') ? ' has-error' : '' }}">
                    <input type="password" id="password-confirm" name="password_confirmation" required />
                    <label for="password-confirm"><i class="material-icons">lock_open</i> Confirm Password</label>
                </div>

                <div class="input-field">
                    <button class="btn waves-effect waves-light" id="submit" type="submit">Register User<i class="right material-icons">send</i></button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/registration.css') }}" />
    <style type="text/css">
        
        .header{
            font-size: 1.5em;
            color: #444;
            text-transform: uppercase;
        }

    </style>
@endpush

@push('scripts')

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("change", "#role", disableMunicipality);
        // $(document).on("click", "#submit", submitForm);
        Materialize.updateTextFields = function() {
            var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea';
            $(input_selector).each(function(index, element) {
                var $this = $(this);
                if ($(element).val().length > 0 || $(element).is(':focus') || element.autofocus || $this.attr('placeholder') !== undefined) {
                    $this.siblings('label').addClass('active');
                } else if ($(element)[0].validity) {
                    $this.siblings('label').toggleClass('active', $(element)[0].validity.badInput === true);
                } else {
                    $this.siblings('label').removeClass('active');
                }
            });
        };

        disableMunicipality();

        var test = false;

        if (test) {
            tester();
        }

        $(".has-error").click(function(){
            $(this).removeClass("has-error");
            $(this).next().addClass("resolved");
            // $(this).next().fade();
        });

    });

    // function submitForm(e){
    //     e.preventDefault();
    //     $.ajax({
    //         url: "/user",
    //         type: "POST",
    //         data: "",
    //         success: function(data){
    //             $('#modal-success > .modal-content > p').after().html(
    //                 'You have successfully added '+data+'to the list of users!'
    //                 );
    //             $('#modal-success').modal('open');
    //         }
    //     });
    // }

    function tester(){
        $('#name').val("asdf");
        $('#username').val("asdf");
        $('#email').val("asdf");
        $('#password').val("asdf");
        $('#password-confirm').val("asdf");
    }

    function disableMunicipality(){
        if($('#role').val() == 1){
            console.log("regular user is chosen");
            $('#municipality').material_select('destroy');
            $("#municipality-field > *").remove();
            $("#municipality-field").prepend(
                '<select name="municipality" id="municipality">'+
                    '<option value="1">Altavas</option>'+
                    '<option value="2">Balete</option>'+
                    '<option value="3">Banga</option>'+
                    '<option value="4">Batan</option>'+
                    '<option value="5">Buruanga</option>'+
                    '<option value="6">Ibajay</option>'+
                    '<option value="7">Kalibo</option>'+
                    '<option value="8">Lezo</option>'+
                    '<option value="9">Libacao</option>'+
                    '<option value="10">Madalag</option>'+
                    '<option value="11">Makato</option>'+
                    '<option value="12">Malay</option>'+
                    '<option value="13">Malinao</option>'+
                    '<option value="14">Nabas</option>'+
                    '<option value="15">New Washington</option>'+
                    '<option value="16">Numancia</option>'+
                    '<option value="17">Tangalan</option>'+
                '</select>'+
                '<label for="municipality">Municipality</label>'   
            );
            $('#municipality').material_select();
            console.log("Municipalities appended");
        }else{
            console.log("admin is chosen");
            $('#municipality').material_select('destroy');
            $("#municipality-field > *").remove();
            $("#municipality-field").prepend(
                '<select name="municipality" id="municipality">'+
                    '<option value="0">OPES</option>'+
                '</select>'+
                '<label for="municipality">Municipality</label>'
            );
            $('#municipality').material_select();
            console.log("OPES appended");
        }
    }

</script>

@endpush