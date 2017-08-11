@extends("layout.navigation")

@section('content')

    <div class="row main">
        <div class="col s12">
            <div class="drawer-header" id="your_drawer">
                <h1><i class="material-icons left">local_offer</i>{{ $tag->name }}</h1>
                <ul class="top-options">
                    <li><a class="btn white waves-effect" href="\home\allfiles">Municipal Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\publicfiles">Public Files</a></li>
                    <li><a class="btn white waves-effect" href="\home\sharedfiles">Admin Files</a></li>
                    @if(Auth::user()->adminRole() != 2 OR is_null(Auth::user()->adminRole()))
                    <li><a class="btn light-green waves-effect upload-btn" href="#upload"><i class="material-icons left">note_add</i> Upload file</a></li>
                    @endif
                </ul>
            </div>

            <hr />

            <ul class="file-drawer" id="your_files">
                @forelse($documents as $file)
                    <li class="drawer-item waves-effect" data-file-id="{{ $file->id }}" data-public="{{ ($file->public OR $file->shared_by_admin)?1:0 }}" data-owned-by-user = "{{ $file->ownedByUser() }}">
                        <i class="material-icons">description</i>
                        <div>
                            <p class="title truncate">{{ $file->filename }}</p>
                            <span class="date">{{ Carbon\Carbon::parse($file->updated_at)->diffForHumans() }} </span>
                        </div>
                        <p class="description">{{ $file->description }}</p>
                        <p class="tag">@if(!is_null($file->tag)){{ $file->tag->name }}@endif</p>
                    </li>
                @empty
                    <li class="none-text">No files tagged under {{ $tag->name }}</li>
                @endforelse
            </ul>

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
        <li><a id="name-sort" class="waves-effect waves-light" href="\home\name" ><i class="material-icons left">sort_by_alpha</i> Name</a></li>
        <li class="active"><a id="date-sort" class="waves-effect waves-light" href="\home\date"><i class="material-icons left">event</i> Date</a></li>
    </ul>
@endsection

@include('modal')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/tag.css') }}" />
@endpush

@push('scripts')
<meta name="_token" content="{{ csrf_token() }}" />
<script type="text/javascript"">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('input[name="_token"]').val()
        }
    });
    $(document).ready(function(){
        console.log(document.referrer);
        // upload file AJAX
        $("#upload_form").off('submit').on('submit', function(e){
            e.preventDefault();
            var filename = $("#filename").val();
            filename = filename.replace("C:\\fakepath\\", "");
            var description = $("#description").val();
            var token = $('input[name="_token"]').val();
            var formData = new FormData($("#upload_form")[0]);
            formData.append('pageFrom', document.referrer);
            $.ajax({
                method: 'POST',
                url: "{{ route('file.store') }}",
                data: formData,
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
                    $("#your_files").html(data[2]);
                    inputClear();
                    setIconsAndColorsItems();
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });


    });
</script>
<!-- <script type="text/javascript" src="{{asset('js/delete-file.js')}}"></script> -->
@endpush
