@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12">
        <!-- ============= Your Drawer =============== -->
        @foreach($owners as $owner)
            @if($owner->id == Auth::user()->id)
            <div class="drawer-header" id="your_header">
                <img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" onerror="this.src='images/default-profile-image.jpg';" />
                <h1>Your drawer</h1>
                <ul class="collapsible collapsible-top-options" data-collapsible="expandable">
                    <li>
                        <div class="collapsible-header"><i class="material-icons">menu</i></div>
                        <div class="collapsible-body">
                            <ul class="top-options">
                                <li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>
                                <li><a class="btn white waves-effect" href="\home\publicfiles">Public Files</a></li>
                                <li><a class="btn white waves-effect" href="\home\sharedfiles">Admin Files</a></li>
                                <li><a class="btn white waves-effect waves-light dropdown-button" id="sort-dropdown-mobile" href="#" data-activates="sort-mobile">Sorted by <i class="material-icons right">arrow_drop_down</i></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <ul class="top-options">
                    <li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\publicfiles">Public Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\sharedfiles">Admin Files</a></li>
                    <li id="sort-label"><a class="btn white waves-effect waves-light dropdown-button" id="sort-dropdown" href="#" data-activates="sort">Sorted by <i class="material-icons right">arrow_drop_down</i></a></li>
                    @if(Auth::user()->adminRole() != 2 OR is_null(Auth::user()->adminRole()))
                    <li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>
                    @endif
                </ul>
            </div>
            <hr />
            @if(!Auth::user()->isAdmin())
            <small class="division-header">Tags Used</small>
            <ul class="tag-drawer">
                @forelse(App\Tag::locationTags(Auth::user()->location) as $tag)
                <li class="tag-item waves-effect"><a href="/tag/{{ $tag->id }}"><i class="material-icons red darken-1 white-text left">local_offer</i>{{ $tag->name }}</a></li>
                @empty
                <li class="tag-item waves-effect"><a href="javascript:void(0)">No Tags Used</a></li>
                @endforelse
            </ul>
            @endif
            <ul class="file-drawer" id="your_files">
            @forelse($allfiles as $allfile)
                @if($allfile->user_id == $owner->id)
                 <li class="drawer-item waves-effect" data-file-id="{{ $allfile->id }}" data-public="{{ ($allfile->public OR $allfile->shared_by_admin)?1:0 }}" data-owned-by-user="{{ $allfile->ownedByUser() }}">
                    <i class="material-icons">description</i>
                    <div>
                        <p class="title truncate">{{ $allfile->filename }}</p>
                        <span class="date">{{ Carbon\Carbon::parse($allfile->updated_at)->diffForHumans() }} </span>
                    </div>
                    <p class="description">{{ $allfile->description }}</p>
                    <p class="tag">@if(!is_null($allfile->tag)){{ $allfile->tag->name }}@endif</p>
                </li>

                @endif
            @empty
                <li>No files available</li>
            @endforelse
            <!-- ====== SEE MORE BUTTON ======= -->
                <!-- <li class="show-more"><a href="#" class="waves-effect waves-light"><span class="plus-icon">+</span> See more</a></li>         -->
            </ul>
            @endif
        @endforeach


        <!-- ============= Other Users =============== -->
        <div id="others">
            
        @forelse($owners as $owner)
            @if($owner->id != Auth::user()->id)
            <div class="drawer-header" id="others_drawer">
                <img src="{{asset($owner->profile_picture)}}" alt="user-img" onerror="this.src='images/default-profile-image.jpg';">
                <h1>{{ $owner->name }}'s drawer</h1>
            </div>
            <hr/>
            <ul class="file-drawer" id="others_files">
            @forelse($allfiles as $allfile)
                @if($allfile->user_id == $owner->id)
                 <li class="drawer-item waves-effect" data-file-id="{{ $allfile->id }}" data-public="{{ ($allfile->public OR $allfile->shared_by_admin)?1:0 }}" data-owned-by-user="{{ $allfile->ownedByUser() }}">
                    <i class="material-icons blue lighten-1">description</i>
                    <div>
                        <p class="title">{{ $allfile->filename }}</p>
                        <span class="date">{{ Carbon\Carbon::parse($allfile->updated_at)->diffForHumans() }} </span>
                    </div>
                    <p class="description">{{ $allfile->description }}</p>
                    <p class="tag">@if(!is_null($allfile->tag)){{ $allfile->tag->name }}@endif</p>
                </li>
                @endif
            @empty
                <li>No files available</li>
            @endforelse
            <!-- ====== SEE MORE BUTTON ======= -->
                <!-- <li class="show-more"><a href="#" class="waves-effect waves-light"><span class="plus-icon">+</span> See more</a></li>         -->
            </ul>
            @endif

        @empty
            <li>No files available</li>
        @endforelse
        </div>
            
            <div class="flash-message">
            @foreach(['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-'.$msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}<a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> </p>
                @endif
            @endforeach
            </div>
        </div>
    </div>

    <ul id="sort" class="dropdown-content">
        <li><a id="name-sort" class="waves-effect waves-light" href="\allfiles\name" ><i class="material-icons left">sort_by_alpha</i> Name</a></li>
        <li class="active"><a id="date-sort" class="waves-effect waves-light" href="\allfiles\date"><i class="material-icons left">event</i> Date</a></li>
    </ul>

@endsection

@include('modal')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}" />
@endpush

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script type="text/javascript"">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('input[name="_token"]').val()
        }
    });
    $(document).ready(function(){
        $(document).on("click", "#name-sort", sortByName);
        $(document).on("click", "#date-sort", sortByDate);

        // upload file AJAX
        $("#upload_form").off('submit').on('submit', function(e){
            e.preventDefault();
            var filename = $("#filename").val();
            filename = filename.replace("C:\\fakepath\\", "");
            var description = $("#description").val();
            var token = $('input[name="_token"]').val();
            $.ajax({
                method: 'POST',
                url: "{{ route('file.store') }}",
                data: new FormData($("#upload_form")[0]),
                processData: false,
                contentType: false,
                success:function(data){
                    $("#upload").modal('close');
                    var msg = data[0].split('|');
                    $("#upload_message > .modal-content > h4").html(msg[0]);
                    $("#upload_message > .modal-content > p").html(msg[1]);
                    $("#upload_message").modal('open');
                    console.log(data);
                    $("#your_files").html(data[1]);
                    getMunicipalTags();
                    inputClear();
                    resetSort();
                    setIconsAndColorsItems();

                }
            });
        });
    });

    function sortByName(e) {
         // sort by name
        e.preventDefault();

        $.ajax({
            method:'GET',
            url:"/allfiles/name", 
            success: function(data){
                console.log(data);

                var cur_id = "";
                var own_files = "";
                $.each(data[2], function (key, value) {
                        var updated_at = value["updated_at"];
                        own_files += '<li class="drawer-item waves-effect" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                            '<i class="material-icons">description</i>'+
                            '<div>'+
                                '<p class="title">'+value["filename"]+'</p>'+
                                                '<span class="date"> '+
                                                    updated_at+
                                                ' </span>'+
                                                '</div>'+
                                                '<p class="description">'+
                                                value["description"]+
                                                '</p>'+
                                        '</li>';
                    cur_id = value["user_id"];
                });
                $("#your_files").html(own_files);

                var other_files = "";
                var pp = "'images/default-profile-image.jpg'";
                $.each(data[1], function (key, owner) {
                    if (owner["id"] != cur_id) {
                        other_files += '<div class="drawer-header" id="others_drawer">'+
                                '<img src="../'+owner["profile_picture"]+'" alt="user-img" />' +
                                '<h1>'+ owner["name"] + '</h1>' +
                                '</div>'+
                                '<hr/>' + 
                                '<ul class="file-drawer" id="others_files">' ;

                        $.each(data[0], function (key2, file) {
                           if (owner["id"] == file["user_id"]) {
                                var updated_at = file["updated_at"];

                                other_files += '<li class="drawer-item waves-effect" data-file-id='+file["id"]+' data-owned-by-user="1">'+ 
                                            '<i class="material-icons blue lighten-1">description</i>' +
                                            '<div>' + 
                                                '<p class="title">' + file["filename"] + '</p>' + 
                                                    '<span class="date">'+updated_at+' </span>' + 
                                            '</div>' + 
                                            '<p class="description">' + file["description"] + '</p>' + 
                                    ' </li>'
                           }
                        });
                        other_files += '</ul>';
                    }
                });

                $("#others").html(other_files);
                $("#sort-dropdown").html('Sorted by Name<i class="material-icons right">arrow_drop_down</i>');
                $("#name-sort").parent().addClass("active");
                $("#date-sort").parent().removeClass("active");

                setIconsAndColorsItems();

            },
            error: function(data){
                console.log(data.responseText);
            }
        });

    }

    function sortByDate(e) {
         // sort by name
        e.preventDefault();

        $.ajax({
            method:'GET',
            url:"/allfiles/date", 
            success: function(data){
                console.log(data);

                var cur_id = "";
                var own_files = "";
                $.each(data[2], function (key, value) {

                        var updated_at = value["updated_at"];
                        own_files += '<li class="drawer-item waves-effect" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                            '<i class="material-icons">description</i>'+
                            '<div>'+
                                '<p class="title">'+value["filename"]+'</p>'+
                                                '<span class="date"> '+
                                                    updated_at+
                                                ' </span>'+
                                                '</div>'+
                                                '<p class="description">'+
                                                value["description"]+
                                                '</p>'+
                                        '</li>';
                    cur_id = value["user_id"];
                });
                $("#your_files").html(own_files);

                var other_files = "";
                var pp = "'images/default-profile-image.jpg'";
                $.each(data[1], function (key, owner) {
                    if (owner["id"] != cur_id) {
                        other_files += '<div class="drawer-header" id="others_drawer">'+
                                '<img src="../'+owner["profile_picture"]+'" alt="user-img" />' +
                                '<h1>'+ owner["name"] + '</h1>' +
                                '</div>'+
                                '<hr/>' + 
                                '<ul class="file-drawer" id="others_files">' ;

                        $.each(data[0], function (key2, file) {
                           if (owner["id"] == file["user_id"]) {
                                var updated_at = file["updated_at"];

                                other_files += '<li class="drawer-item waves-effect" data-file-id='+file["id"]+' data-owned-by-user="1">'+ 
                                            '<i class="material-icons blue lighten-1">description</i>' +
                                            '<div>' + 
                                                '<p class="title">' + file["filename"] + '</p>' + 
                                                    '<span class="date">'+updated_at+' </span>' + 
                                            '</div>' + 
                                            '<p class="description">' + file["description"] + '</p>' + 
                                    ' </li>'
                           }
                        });
                        other_files += '</ul>';
                    }
                });

                $("#others").html(other_files);
                $("#sort-dropdown").html('Sorted by Date<i class="material-icons right">arrow_drop_down</i>');
                $("#date-sort").parent().addClass("active");
                $("#name-sort").parent().removeClass("active");

                setIconsAndColorsItems();

            },
            error: function(data){
                console.log(data.responseText);
            }
        });

    }

</script>
@endpush
