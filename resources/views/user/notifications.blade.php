@extends("layout.navigation")

@section('content')

	<div class="row">
		<div class="col s12 m2">
			
		</div>
		<div class="col s12 m10">
			<h1 class="page-name"><i class="material-icons">notifications</i> Notifications</h1>
			<div class="notification-container">
				<article class="notification">
					
				</article>
			</div>
		</div>
	</div>

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/notifications.css') }}">
@endpush

@push('scripts')
@endpush