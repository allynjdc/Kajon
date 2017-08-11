@extends("layout.navigation")

@section('content')


    <div class="row main">
        <div class="col s12 m4 offset-m4 relative">
            <div><br></div>
            <div id="side-profile">
                <!-- <div class="paper"> -->
                    <div class="profile-picture-holder">
                        <img src="{{asset(Auth::user()->profile_picture)}}" alt="user-image" onerror="this.src='images/default-profile-image.jpg';">
                        <a href="#editThumbnail" id="profpic" class="btn waves-effect waves-light"><span class="glyphicon glyphicon-edit"></span> Change Photo</a>
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
                    <div class="details">
                        <h2 class="name">{{Auth::user()->name}}</h2>
                        <!-- <p class="admin">Administrator</p> -->
                        <p class="username">{{Auth::user()->username}} </p>
                        <p class="date-joined">{{Auth::user()->created_at->diffForHumans()}} </p>
         <!-- =================== replace location or provide the equivalent of the number to the location =================== -->
                        <p class="location">{{Auth::user()->location()}} </p>
                    </div>
                <!-- </div> -->
                <div class="top-statistics">
                    <section class="points" id="points" data-target="point-breakdown">
                        @if(Auth::user()->isAdmin())
                        <p>&#x221e;</p>
                        @else
                        <p>{{$points}}</p>
                        @endif
                        <p>Points</p>
                    </section>
                    <section class="owned" id="owned">
                        <p>{{$owned}}</p>
                        <p>Owned Files</p>
                    </section>
                    <section class="shared" id="shared">
                        <p>{{$shared}}</p>
                        <p>Shared Files</p>
                    </section>
                    <section class="public hide">
                        <p>10</p>
                        <p>Public Files</p>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="modal form-modal" id="editThumbnail">
        <div class="modal-content">
            <h3 class="modal-header" id="modalLabel">Change Profile Picture</h3>
            <form action="/editThumbnail/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!-- <div class="input-field">
                    <input type="hidden" name="profpic" value="{{ Auth::user()->id }}">
                    <input type="file" name="image" accept=".png, .jpeg, .jpg" />
                </div> -->
                <label class="input-file{{ $errors->has('image') ? ' has-error' : '' }}">
                    <span class="waves-effect waves-light light-blue"><i class="material-icons large">image</i></span>
                    <input type="file" name="image" required="required" accept=".png, .jpeg, .jpg, .PNG, .JPEG, .JPG" />
                    <span class="name"></span>
                </label>
                <button type="submit" class="btn waves-effect waves-light"> Change Picture </button>
            </form>
        </div>
    </div>

    <!-- MODAL FOR VIEW FILE -->
    <!-- if you click the notice in Point Breakdown -->
    <!-- but Sir Nilo doesn't want it to be clickable, so I'll just comment out this. :) -->
    <!--     
    <div id="view-file" class="modal modal-view-file">
        <div class="modal-content">

            <i class="material-icons large blue lighten-1 file-icon">description</i>
            <h2 class="file-name">Filename</h2>
            <p class="description">File description and other necessary details.</p>
            <p class="date"></p>

            <ul class="contributors-container">
                <li>
                    <div class="chip waves-effect waves-light"><a href="#"><img src="{{asset('images/sample.jpg')}}">Name</a></div>
                </li>
                <li>
                    <div class="chip waves-effect waves-light"><a href="#"><img src="{{asset('images/sample.jpg')}}">Name</a></div>
                </li>
                <li>
                    <div class="chip additional waves-effect waves-light" data-target="view-contributors">1</div>
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <button data-target="delete-conf" class="btn waves-effect waves-light red"> <i class="material-icons">delete</i></button>
            <button class="btn waves-effect waves-light" data-target="update"> <i class="material-icons">REVISE</i></button>
            <button data-target="downloads" class="btn waves-effect waves-light"> <i class="material-icons">file_download</i></button>
        </div>
    </div> 
    -->
    <!-- END OF MODAL -->

    <div id="view-contributors" class="modal bottom-sheet">
        <div class="modal-content">
            <h1>Contributors</h1>
            <ul class="contributors-container">
                <li>
                    <a href="#">
                        <img src="images/sample.jpg" class="contributor-pic">
                        <p class="contributor-name">Clyde Joshua Delgado</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="images/sample.jpg" class="contributor-pic">
                        <p class="contributor-name">John Lucas Remedio</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="images/sample.jpg" class="contributor-pic">
                        <p class="contributor-name">Chris Doe</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div id="delete-conf" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h5>Are you sure you are going to delete this file?</h5>
            <div>
                <form>
                    {{METHOD_FIELD('DELETE')}}
                    <button id="delete-btn" class="btn waves-effect waves-light red">Delete <i class="right material-icons">delete</i></button>
                </form>
                <button class="btn waves-effect waves-light">Cancel <i class="right material-icons">cancel</i></button>
            </div>
        </div>
    </div>

    <div id="downloads" class="modal">
        <div class="modal-content">
            <h5 class="center">Downloads</h5>
            <h2 class="none-text">Empty</h2>
        </div>
    </div>

    <div id="uploads" class="modal">
        <div class="modal-content">
            <h5 class="center">Uploads</h5>
            <h2 class="none-text">Empty</h2>
        </div>
    </div>

    <!--- FOR UPDATE -->
    <div id="update" class="modal bottom-sheet">
        <div class="modal-content">
            <form method="PATCH">
                {{ csrf_field() }}
                {{METHOD_FIELD('PUT')}}
                <div class="file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input type="file" />
                    </div>
                    <div class="file-path-wrapper">
                        <input name="filename" class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <div class="input-field">
                    <input type="text" id="description" name="description" value="" />
                    <label for="description"><i class="material-icons">description</i> File Description</label>
                </div>
                <div class="input-field">
                    <button class="btn waves-effect waves-light" id="submit" type="submit">Submit <i class="right material-icons">send</i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FOR POINT BREAKDOWN -->
    <div id="point-breakdown" class="modal">
        <div class="modal-content">
            @if(Auth::user()->isAdmin())
                    This is an admin account and thus cannot have points.
            @else
            @if( (count($idv) != 0) || (count($mun) != 0) )
            <h1>Point statistics</h1>
            <ul class="file-drawer">
            @foreach($idv as $complied)
                <li class="drawer-item waves-effect" data-target="view-file">
                    <i class="material-icons circle red lighten-1">description</i>
                    <div>
                        <p class="title truncate">{{$complied->reminderBy->title}}</p>
                        <span class="date">{{$complied->date_complied}}</span>
                    </div>
                    <p class="description">{{$complied->reminderBy->description}}
                    </p>
                    <p class="points five">{{$complied->score}}</p>
                </li>
            @endforeach
            @foreach($mun as $complied)
                <li class="drawer-item waves-effect" data-target="view-file">
                    <i class="material-icons circle red lighten-1">description</i>
                    <div>
                        <p class="title truncate">{{$complied->reminderBy->title}}</p>
                        <span class="date">{{$complied->date_complied}}</span>
                    </div>
                    <p class="description">
                        {{$complied->reminderBy->description}}
                    </p>
                    <p class="points five">{{$complied->score}}</p>
                </li>
            @endforeach
            </ul>
            @else
                No points earned.
            @endif
            @endif
        </div>
    </div>
    <!-- END OF THE MODAL -->

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">

        $("input[type='file']").change(function(){
            $(this).next().html($(this).val().substring($(this).val().lastIndexOf("\\") + 1, $(this).val().length));
            if ($(this).val()) {
                $(this).parent().next().show();
            } else{
                $(this).parent().next().hide();
            }
        });

    </script>
@endpush
