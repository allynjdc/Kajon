@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12">
        <!-- ============= Your Drawer =============== -->
            <div class="drawer-header" id="your_drawer">
                <img src="{{asset(Auth::user()->profile_picture)}}" alt="user-img" onerror="this.src='images/default-profile-image.jpg';" />
                <h1>Your drawer</h1>
                <ul class="top-options">
                    <li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\publicfiles">Public Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\sharedfiles">Admin Files</a></li>
                    @if(Auth::user()->adminRole() != 2 OR is_null(Auth::user()->adminRole()))
                    <li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>
                    @endif
                </ul>
            </div>
            <hr/>
            <ul class="file-drawer" id="your_files">
            @forelse($sharedowner as $allfile)
                 <li class="drawer-item waves-effect" data-file-id="{{ $allfile->id }}" data-public="{{ ($allfile->public OR $allfile->shared_by_admin)?1:0 }}" data-owned-by-user="{{ $allfile->ownedByUser() }}">
                    <i class="material-icons">description</i>
                    <div>
                        <p class="title truncate">{{ $allfile->filename }}</p>
                        <span class="date">{{ Carbon\Carbon::parse($allfile->updated_at)->diffForHumans() }} </span>
                    </div>
                    <p class="description">{{ $allfile->description }}</p>
                    <p class="tag">@if(!is_null($allfile->tag)){{ $allfile->tag->name }}@endif</p>
                </li>
            @empty
                <li>No available files</li>
            @endforelse
            </ul>

        <!-- ============= Other Users =============== -->
        @forelse($owners as $owner)
            @if($owner->id != Auth::user()->id)
            <div class="drawer-header">
                <img src="{{asset($owner->profile_picture)}}" alt="user-img" onerror="this.src='images/default-profile-image.jpg';">
                <h1>{{ $owner->name }}'s drawer</h1>
            </div>
            <hr/>
            <ul class="file-drawer">
            @forelse($shared as $allfile)
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
            </ul>
            @endif

        @empty
            <li>No files available</li>
        @endforelse
            
            <div class="flash-message">
            @foreach(['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-'.$msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </p>
                @endif
            @endforeach
            </div>
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
<script type="text/javascript"">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('input[name="_token"]').val()
        }
    });
    $(document).ready(function(){

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
                    $("#your_files").html(data[1]);
                    setIconsAndColorsItems();
                    inputClear();
                }
            });
        });
    });
</script>
@endpush
