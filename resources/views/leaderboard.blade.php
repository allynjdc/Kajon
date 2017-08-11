@extends("layout.navigation")

@section('content')
    <div class="row">
        <div class="col s12">
        @if(($municipal_ranks != null) AND ($alluser_ranks != null) AND ($overall_ranks != null))
            <h1 class="header">All Municipalities</h1>
            <ul class="municipalities">
            @foreach ($municipal_ranks as $rank => $name)
                <li>
                    <span class="icon"></span>
                    <span class="name">{{$name['name']}}</span>
                    <span class="points">{{$name['score']}}</span>
                </li> 
            @endforeach
            </ul>
            
            @if(count($alluser_ranks))
            <h1 class="header">All Users</h1>
            <ul class="employees-container">
            @foreach ($alluser_ranks as $rank => $name)
                <li class="employee">
                    <img src="{{asset($name['profile_picture'])}}" alt="user-picture">
                    <span class="name">{{$name['name']}}</span>
                    <span class="points">{{$name['score']}}</span>
                </li> 
            @endforeach
            </ul>
            @endif

            @if(count($overall_ranks))
            <h1 class="header">Overall</h1>
            <ul class="employees-container">
            @foreach ($overall_ranks as $rank => $name)
                <li class="employee">
                    <img src="{{asset($name['profile_picture'])}}" alt="user-picture">
                    <span class="name">{{$name['name']}}</span>
                    <span class="points">{{$name['score']}}</span>
                </li> 
            @endforeach
            </ul>
            @endif

        @else
            <span class="none-text">There's nothing to rank.</span>
        @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/leaderboard.css') }}" />

@endpush

@push('scripts')
<script type="text/javascript">
        $(document).ready(function(){
            var municipalities = $(".municipalities > li .icon");
            console.log(municipalities);
            $(".municipalities > li .icon").html(function(){
                var municipality = $(this).next().html();

                if (municipality == "Altavas") {
                    $(this).addClass("red lighten-1");
                    return "A";
                } else if (municipality == "Balete") {
                    $(this).addClass("pink lighten-1");
                    return "Bl";
                } else if (municipality == "Banga") {
                    $(this).addClass("purple lighten-1");
                    return "Bn";
                } else if (municipality == "Batan") {
                    $(this).addClass("deep-purple lighten-1");
                    return "Bt";
                } else if (municipality == "Buruanga") {
                    $(this).addClass("indigo lighten-1");
                    return "Bu";
                } else if (municipality == "Ibajay") {
                    $(this).addClass("blue lighten-1");
                    return "I";
                } else if (municipality == "Kalibo") {
                    $(this).addClass("light-blue lighten-1");
                    return "K";
                } else if (municipality == "Lezo") {
                    $(this).addClass("cyan lighten-1");
                    return "Le";
                } else if (municipality == "Libacao") {
                    $(this).addClass("teal lighten-1");
                    return "La";
                } else if (municipality == "Madalag") {
                    $(this).addClass("green lighten-1");
                    return "Md";
                } else if (municipality == "Makato") {
                    $(this).addClass("light-green lighten-1");
                    return "Mk";
                } else if (municipality == "Malay") {
                    $(this).addClass("lime lighten-1");
                    return "My";
                } else if (municipality == "Malinao") {
                    $(this).addClass("yellow lighten-1");
                    return "Mo";
                } else if (municipality == "Nabas") {
                    $(this).addClass("amber lighten-1");
                    return "Na";
                } else if (municipality == "New Washington") {
                    $(this).addClass("orange lighten-1");
                    return "Nw";
                } else if (municipality == "Numancia") {
                    $(this).addClass("deep-orange lighten-1");
                    return "Nu";
                } else if (municipality == "Tangalan") {
                    $(this).addClass("blue-grey lighten-1");
                    return "T";
                } else {
                    $(this).addClass("red lighten-1");
                    return "O";
                }
            });
        });
    </script>
@endpush
