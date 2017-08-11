@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12">
            
            @if(empty($search))
            <div class="drawer-header" id="your_header">
                <img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" onerror="this.src='images/default-profile-image.jpg';"/>
                <h1> Your kajon</h1>
                <ul class="collapsible collapsible-top-options" data-collapsible="expandable">
                    <li>
                        <div class="collapsible-header"><i class="material-icons">keyboard_arrow_down</i></div>
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
        
                @if(count(Auth::user()->usedTags()))
                <hr>
                <small class="division-header">Tags Used</small>
                <ul class="tag-drawer">
                    @forelse(Auth::user()->usedTags() as $tag)
                    <li class="tag-item waves-effect"><a href="/tag/{{ $tag->id }}"><i class="material-icons red darken-1 white-text left">local_offer</i>{{ $tag->name }}</a></li>
                    @empty
                    <li class="tag-item waves-effect"><a href="javascript:void(0)">No Tags Used</a></li>
                    @endforelse
                </ul>
                @endif

            @else
            <div class="drawer-header search" id="your_header">
                <h1>You searched for <span class="keyword">{{$search}}</span></h1>
            </div>
            @endif

            @if(count($files) || count($allfiles))
                @if(count($files))
                    <hr />
                    @if(empty($search))
                    <small class="division-header">Recent Files</small>
                    @else
                    <small class="division-header">Your Files similar to {{$search}}</small>
                    @endif
                    <ul class="file-drawer" id="your_files">
                    @forelse($files as $file)
                        <li class="drawer-item waves-effect @if($file->deleted) deleted @endif" data-file-id="{{ $file->id }}" data-public="{{ ($file->public OR $file->shared_by_admin)?1:0 }}" data-owned-by-user = "{{ $file->ownedByUser() }}">
                            <i class="material-icons">description</i>
                            <div>
                                <p class="title truncate">{{ $file->filename }}</p>
                                <span class="date">{{ Carbon\Carbon::parse($file->updated_at)->diffForHumans() }} </span>
                            </div>
                            <p class="description">{{ $file->description }}</p>
                            <p class="tag">@if(!is_null($file->tag)){{ $file->tag->name }}@endif</p>
                        </li>
                    @empty
                        @if(empty($search))
                            <li class="none-text"><i class="material-icons left">sentiment_dissatisfied</i>You have no files in your kajon</li>
                        @else
                            <li class="none-text"><i class="material-icons left">sentiment_dissatisfied</i>You have no files containing "{{$search}}"</li>
                        @endif
                    @endforelse
                    </ul>
                @endif

                @if(count($allfiles))
                    <hr/>
                    @if(empty($search))
                    <small class="division-header">Your Files</small>
                    @else
                    <small class="division-header">Other's Files</small>
                    @endif
                    <div id="load-data">
                        <ul class="file-drawer" id="all_files">

                        @forelse($allfiles as $allfile) 
                             <li class="drawer-item waves-effect allfile-item @if($allfile->deleted) deleted @endif" data-public="{{ ($allfile->public OR $allfile->shared_by_admin)?1:0 }}" data-file-id="{{ $allfile->id }}" data-owned-by-user= "{{ $allfile->ownedByUser() }}">
                                <i class="material-icons">description</i>
                                <div>
                                    <p class="title truncate">{{ $allfile->filename }}</p>
                                    <span class="date">{{ Carbon\Carbon::parse($allfile->updated_at)->diffForHumans() }} </span>
                                </div>
                                <p class="description">{{ $allfile->description }}</p>
                                <p class="tag">@if(!is_null($allfile->tag)){{ $allfile->tag->name }}@endif</p>
                            </li>
                        @empty
                            @if(empty($search))
                                <li class="none-text"><i class="material-icons left">sentiment_dissatisfied</i>You have no files in your kajon for the moment.</li>
                            @else
                                <li class="none-text"><i class="material-icons left">sentiment_dissatisfied</i>There are no other files containing "{{$search}}"</li>
                            @endif
                        @endforelse
                            @if($allfile_count > 9)
                                <li class="show-more" id="remove-row"><a id="btn-more" data-updated_at="{{ $allfile->updated_at }}" data-id="{{ $allfile->id }}" class="waves-effect waves-light"><span class="plus-icon">+</span> See more</a></li>
                            @endif
                            <!-- <button id="btn-more" data-id="{{ $allfile->id }} " >See More</button> -->
                        </ul>
                    </div>
                @endif
            @else
                <hr />
                @if(empty($search))
                    <span class="none-text"><i class="material-icons left">sentiment_dissatisfied</i> There is nothing to show</span>
                @else
                    <span class="none-text"><i class="material-icons left">sentiment_dissatisfied</i>There are no results</span>
                @endif
            @endif

            <div class="flash-message download-message">
            @foreach(['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-'.$msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}<a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> </p>
                @endif
            @endforeach
         

            </div>

        </div>
    </div>

    <ul id="sort" class="dropdown-content">
        <li><a id="name-sort" class="waves-effect waves-light" href="\home\name" ><i class="material-icons left">sort_by_alpha</i> Name</a></li>
        <li class="active"><a id="date-sort" class="waves-effect waves-light" href="\home\date"><i class="material-icons left">event</i> Date</a></li>
    </ul>

    <ul id="sort-mobile" class="dropdown-content">
        <li><a id="name-sort" class="waves-effect waves-light" href="\home\name" ><i class="material-icons left">sort_by_alpha</i> Name</a></li>
        <li class="active"><a id="date-sort" class="waves-effect waves-light" href="\home\date"><i class="material-icons left">event</i> Date</a></li>
    </ul>
@endsection
@include('modal')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}" />
@endpush

@push('scripts')
<meta name="_token" content="{{ csrf_token() }}" />
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('input[name="_token"]').val()
        }
    });
    $(document).ready(function(){
        $(document).on("click", "#name-sort", sortByName);
        $(document).on("click", "#date-sort", sortByDate);
        $(document).on("click", "#btn-more", loadMore);        
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
                    addYourFiles();
                    $("#your_files").html(data[1]);
                    $("#all_files").html(data[2]);
                    getTags();
                    if(data[3] > 9){
                        addSeeMoreButton();
                    }
                    inputClear();
                    resetSort();
                    setIconsAndColorsItems();
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });
    });

    function loadMore(e) {
        e.preventDefault();

        var id = $("#btn-more").data('id');
        var updated_at = $("#btn-more").data('updated_at');
        var file_list = new Array();
        var current = "name";
        if($("#date-sort").parent().hasClass("active")){
            current = "date"
        }
        $('#btn-more').html("Loading. . . ");

        $(".allfile-item").each(function(){
            file_list.push($(this).data('file-id'));
        });

        $.ajax({
            method: "POST",
            url: '{{ url("/home/loadmore")}} ',
            data: {id:id, _token:"{{csrf_token()}} ", updated_at: updated_at, file_list: file_list, current: current},
            success:function(data){
                var btn = '';
                var num = data[1];
                var limit = 9;
                var left = 0;
                var dID = '';
                var fname = '';

                console.log('total num: ' + num);
                console.log(data);
                console.log('num: ' + num);

                if(data != ''){
                    var allfiles = '';
                    $.each(data[0], function(key, value){
                        var public = 0;
                        var updated_at = value["updated_at"];
                        if(value["shared_by_admin"] || value["public"]){
                            public = 1;
                        }
                        allfiles +=  '<li class="drawer-item waves-effect allfile-item" data-public="'+public+'" data-file-id="'+ value["id"] + '" data-owned-by-user= "1">' +
                            '<i class="material-icons">description</i>' +
                            '<div>' + 
                                '<p class="title truncate">' +  value["filename"] + '</p>' + 
                                '<span class="date">' + value["parsed_updated_at"] + '</span>' +
                            '</div>' +
                            '<p class="description">'+
                                 value["description"]  + 
                            '</p>'+
                        '</li>';
                        dID = value["id"];
                    });

                    $(allfiles).insertBefore("#remove-row");
                    if (num > limit) {
                        $("#btn-more").data("id", dID);
                        $("#btn-more").data("updated_at", updated_at);
                        $("#btn-more").html("<span class='plus-icon'>+</span>See More");
                    }
                    else{
                        $('#remove-row').css("display", "none");
                    }
                }
                else{
                    $('#remove-row').css("display", "none");
                }

                setIconsAndColorsItems();

            }, 
            error: function(data){
                console.log(data.responseText);
            }
        });
    }

    function sortByName(e) {
         // sort by name
        e.preventDefault();

        $.ajax({
            method:'GET',
            url:"/home/name", 
            success: function(data){
                console.log(data);

                var own_files = "";
                $.each(data[0], function(key,value){
                    var updated_at = value["updated_at"]["date"];
                    own_files += '<li class="drawer-item waves-effect" data-updated_at="'+updated_at+'" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                                '<i class="material-icons">description</i>'+
                                '<div>'+
                                    '<p class="title">'+value["filename"]+'</p>'+
                                    '<span class="date"> '+
                                        value["parsed_updated_at"]+
                                    ' </span>'+
                                '</div>'+
                                '<p class="description">'+
                                    value["description"]+
                                '</p>'+
                            '</li>';
                });
                $("#your_files").html(own_files);

                var all_files = "";
                $.each(data[1], function(key, value){
                    var updated_at = value["updated_at"]["date"];
                    all_files += '<li class="drawer-item waves-effect allfile-item" data-updated_at="'+updated_at+'" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                                    '<i class="material-icons">description</i>'+
                                    '<div>'+
                                        '<p class="title">'+value["filename"]+'</p>'+
                                        '<span class="date"> '+
                                            value["parsed_updated_at"]+
                                        ' </span>'+
                                    '</div>'+
                                    '<p class="description">'+
                                        value["description"]+
                                    '</p>'+
                                '</li>';
                });
                $("#all_files").html(all_files);
                addSeeMoreButton();
                setIconsAndColorsItems();
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

    function sortByDate (e){
        e.preventDefault();
        $.ajax({
            method:'GET',
            url:"/home/date", 
            success: function(data){
                console.log(data);
                var own_files = "";
                $.each(data[0], function(key,value){
                    var updated_at = value["updated_at"]["date"];
                    own_files += '<li class="drawer-item waves-effect allfile-item" data-updated_at="'+updated_at+'" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                                '<i class="material-icons">description</i>'+
                                '<div>'+
                                    '<p class="title">'+value["filename"]+'</p>'+
                                    '<span class="date"> '+
                                        value["parsed_updated_at"]+
                                    ' </span>'+
                                '</div>'+
                                '<p class="description">'+
                                    value["description"]+
                                '</p>'+
                            '</li>';
                });
                $("#your_files").html(own_files);

                var all_files = "";
                $.each(data[1], function(key, value){
                    var updated_at = value["updated_at"]["date"];
                    all_files += '<li class="drawer-item waves-effect" data-updated_at="'+updated_at+'" data-file-id='+value["id"]+' data-owned-by-user="1">'+
                                    '<i class="material-icons">description</i>'+
                                    '<div>'+
                                        '<p class="title">'+value["filename"]+'</p>'+
                                        '<span class="date"> '+
                                            value["parsed_updated_at"]+
                                        ' </span>'+
                                    '</div>'+
                                    '<p class="description">'+
                                        value["description"]+
                                    '</p>'+
                                '</li>';
                });
                $("#all_files").html(all_files);
                addSeeMoreButton();
                setIconsAndColorsItems();
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
<script type="text/javascript" src="{{asset('js/add-tag.js')}}"></script>
<script type="text/javascript" src="{{asset('js/edit-tag.js')}}"></script>
@endpush
