@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12">
            @if(empty($search))
            <div class="drawer-header" id="your_drawer">
                <img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" />
                <h1> Your drawer</h1>
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
                    <li><a class="btn white waves-effect" href="\home\sharedfiles">Shared Files</a></li>
                    <li class="right"><a class="btn white waves-effect waves-light dropdown-button" id="sort-dropdown" href="#" data-activates="sort">Sorted by <i class="material-icons right">arrow_drop_down</i></a></li>
                    <li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>
                </ul>
            </div>
            <hr>
            <small class="division-header">Recent Files</small>
            @else
            <div class="drawer-header search" id="your_drawer">
                <h1>You searched for <span class="keyword">{{$search}}</span>.</h1>
            </div>
            <small class="division-header">Searched Files</small>
            <hr>
            @endif
            <ul class="file-drawer" id="your_files">
                @forelse($files as $file)
                    <li class="drawer-item waves-effect" data-target="view-file" data-id="{{$file->id}}">
                        <i class="material-icons">description</i>
                        <div>
                            <p class="title truncate">{{ $file->filename }}</p>
                            <span class="date">{{ Carbon\Carbon::parse($file->created_at)->diffForHumans() }} </span>
                        </div>
                        <p class="description">
                            {{ $file->description }}
                        </p>
                    </li>

                @empty
                    <li>You have no files</li>
                @endforelse

            </ul>
        </div>
    </div>

    <ul id="sort" class="dropdown-content">
        <li><a id="name-sort" class="waves-effect waves-light" href="\home\name" ><i class="material-icons left">sort_by_alpha</i> Name</a></li>
        <li class="active"><a id="date-sort" class="waves-effect waves-light" href="\home\date"><i class="material-icons left">event</i> Date</a></li>
    </ul>

@endsection

@include('modal')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}" />
@endpush

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('input[name="_token"]').val()
        }
    });
    $(document).ready(function(){
        // view file AJAX
        $(document).on("click", ".drawer-item", viewFile);

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
                    $("input[name='description']").val("");
                    $("input[name='filename']").val("");
                    $("#upload").modal('close');
                    var msg = data[0].split('|');
                    $("#upload_message > .modal-content > h4").html(msg[0]);
                    $("#upload_message > .modal-content > p").html(msg[1]);
                    $("#upload_message").modal('open');
                    $(".drawer-header").html(
                        '<img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" />'+
                        '<h1>{{ Auth::user()->name }}&apos;s drawer</h1>'+
                        '<ul class="top-options">'+
                            '<li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>'+
                            '<li class="right"><a class="btn white waves-effect waves-light dropdown-button" id="sort-dropdown" href="#" data-activates="sort">Sorted by <i class="material-icons right">arrow_drop_down</i></a></li>'+
                            '<li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>'+
                        '</ul>'
                    );
                    var own_files = "";
                    console.log(data[1]["data"]);
                    $.each(data[1]["data"], function(key,value){
                        var updated_at = value["updated_at"];
                        own_files += '<li class="drawer-item waves-effect" data-target="view-file" >'+
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
                    });
                    $("#your_files").html(own_files);

                    setIconsAndColorsItems();
                    
                }
            });
        });
    });

    function viewFile(e){
        e.preventDefault();
    }
</script>
<script type="text/javascript" src="{{asset('js/view-file.js')}}"></script>
<!-- <script type="text/javascript" src="{{asset('js/delete-file.js')}}"></script> -->
@endpush
