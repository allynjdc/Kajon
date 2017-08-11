@extends("layout.navigation")

@section('content')  

	<div class="row">
		<div class="col s12">
			<a href="/reminders" class="back-to-reminders"><i class="material-icons left">keyboard_arrow_left</i>Reminders</a>
			<div class="reminder">
				<div>
					<h2 class="title"><i class="material-icons incomplete left">assignment</i>{{$reminder->title}}</h2>
					<p class="from">Notice from <img src="{{asset($reminder->user->profile_picture)}}"> {{$reminder->user->name}} due on {{date('m-d-Y', strtotime($reminder->due_date))}}</p>
				</div>
				<p class="creation">{{date('m-d-Y', strtotime($reminder->created_at))}}</p>
			</div>
		
			@if($reminder->municipality == '0')

			<ul class="collapsible" data-collapsible="expandable">
				@if(count($user_compliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Accepted Compliants</div>
					<div class="collapsible-body">
						<div class="complied-division">
							<ul class="complied">
								@foreach($user_compliants as $compliant)
			 						<li><img src="{{asset($compliant->user->profile_picture)}}"><span class="name">{{$compliant->user->name}}</span></li>
			 					@endforeach
							</ul>
						</div>
					</div>
				</li>
				@endif
				@if(count($user_rcompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Rejected Compliants</div>
					<div class="collapsible-body">
						<div class="complied-division">
							<ul class="complied">
								@foreach($user_rcompliants as $compliant)
			 						<li><img src="{{asset($compliant->user->profile_picture)}}"><span class="name">{{$compliant->user->name}}</span></li>
			 					@endforeach
							</ul>
						</div>
					</div>
				</li>
				@endif
				@if(count($user_pcompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Pending Compliants</div>
					<div class="collapsible-body">
						<form action="/compliances/{{$reminder->id}}/action">
							<h3 class="complied-title">Pending Compliants</h3>
							<ul class="employees">
								<?php $count = 0 ?>
								@foreach($user_pcompliants as $compliant)
									<li><input type="checkbox" id="e{{$count}}" name="compliants[]" value="{{$compliant->user_id}}" /> <label class="waves-effect waves-light" for="e{{$count}}"><img src="{{asset($compliant->user->profile_picture)}}">{{$compliant->user->name}}</label></li>
									<?php $count++ ?>
								@endforeach
							</ul> 
							<button class="btn waves-effect waves-light light-green right" name="accept" type="submit" value="accept" ><i class="material-icons left">check</i>Approve</button>
							<button class="btn waves-effect waves-light red right" type="button" name="reject" value="reject" data-target="reject-compliant" data-reminder-id="{{ $reminder->id }}" data-compliants="compliants[]" data-reject="reject"><i class="material-icons left">clear</i>Reject</button>

							<!--- Password Confirmation Modal 
								  DONT EVER TRY TO MOVE THIS CODE TO OTHER LINE -->
							<div class="modal delete-modal" id="reject-compliant">
								<div class="modal-content center">
									<h1 class="modal-header center"> Are you sure you want to reject them? </h1>
									<p class="center"> Enter your Password for Confirmation </p>
									<div class="btn-group-horiz">
										<form action="/compliances/{{$reminder->id}}/action">
											<input type="password" id="password" name="password">
											<button class="btn waves-effect waves-light grey close-delete modal-action modal-close" name="cancel" value="cancel" type="button"><i class="material-icons left">cancel</i>Cancel</button>
											<button type="submit" class="btn waves-effect waves-light red" name="reject" value="reject"><i class="material-icons left">clear</i>Reject</button>
										</form>
									</div>
								</div>
							</div>
							<!--- End of Modal -->
						</form>
					</div>
				</li>
				@endif
				@if(count($user_ncompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Not yet complied</div>
					<div class="collapsible-body">
						<form>
							<h3 class="complied-title">Not Yet Complied</h3>
							<ul class="employees">
								@foreach($user_ncompliants as $compliant)
									<!-- <li><img src="{{asset('images/sample.jpg')}}">{{$compliant->user->name}}</li> -->
									<li><img src="{{asset($compliant->user->profile_picture)}}"><span class="name">{{$compliant->user->name}}</span></li>
								@endforeach
							</ul>
						</form>
					</div>
				</li>
				@endif
			</ul>

			@else 

			<ul class="collapsible" data-collapsible="expandable">
				@if(count($municipal_compliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Accepted Compliants</div>
					<div class="collapsible-body">
						<ul class="municipalities">
							@foreach($municipal_compliants as $compliant)
								<li><span class="icon"></span><span class="name">{{$compliant->location($compliant->location)}}</span></li>
							@endforeach
						</ul>
					</div>
				</li>
				@endif
				@if(count($municipal_rcompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Rejected Compliants</div>
					<div class="collapsible-body">				
						<ul class="municipalities">
							@foreach($municipal_rcompliants as $compliant)
								<li><span class="icon"></span><span class="name">{{$compliant->location($compliant->location)}}</span></li>
							@endforeach
						</ul>
					</div>
				@endif
				</li>
				@if(count($municipal_pcompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Pending Compliants</div>
					<div class="collapsible-body">
						<form action="/compliances/{{$reminder->id}}/action">
							<ul class="municipalities form">
								<?php $count = 0 ?>
								@foreach($municipal_pcompliants as $compliant)
									<li><input type="checkbox" id="m{{$count}}" name="compliants[]" value="{{$compliant->location}}" /> <label class="waves-effect waves-light" for="m{{$count}}"><span class="icon"></span><span class="name">{{$compliant->location($compliant->location)}}</span></label></li>
									<?php $count++ ?>
								@endforeach
							</ul>

							<button class="btn waves-effect waves-light light-green right" type="submit" name="accept" value="accept" ><i class="material-icons left">check</i>Approve</button>
							<button class="btn waves-effect waves-light red right" type="button" name="reject" value="reject" data-target="reject-compliant" data-reminder-id="{{ $reminder->id }}" data-compliants="compliants[]" data-reject="reject"><i class="material-icons left">clear</i>Reject</button>

							<!--- Password Confirmation Modal 
								  DONT EVER TRY TO MOVE THIS CODE TO OTHER LINE -->
							<div class="modal delete-modal" id="reject-compliant">
								<div class="modal-content center">
									<h1 class="modal-header center"> Are you sure you want to reject them? </h1>
									<p class="center"> Enter your Password for Confirmation </p>
									<div class="btn-group-horiz">
										<form action="/compliances/{{$reminder->id}}/action">
											<input type="password" id="password" name="password">
											<button class="btn waves-effect waves-light grey close-delete modal-action modal-close" name="cancel" value="cancel" type="button"><i class="material-icons left">cancel</i>Cancel</button>
											<button type="submit" class="btn waves-effect waves-light red" name="reject" value="reject"><i class="material-icons left">clear</i>Reject</button>
										</form>
									</div>
								</div>
							</div>
							<!--- End of Modal -->

						</form>				
					</div>				
				</li>
				@endif
				@if(count($municipal_ncompliants) > 0)
				<li>
					<div class="collapsible-header active waves-effect waves-light"><i class="material-icons">keyboard_arrow_down</i>Not Yet Complied</div>
					<div class="collapsible-body">
						<form>
							<ul class="municipalities">
								@foreach($municipal_ncompliants as $compliant)
									<li><span class="icon"></span><span class="name">{{$compliant->location($compliant->location)}}</span></li>
								@endforeach
							</ul>
						</form>
					</div>				
				</li>
				@endif
			</ul>

			@endif

		</div>
	</div>

@endsection

@push('styles')
	<link rel="stylesheet" type="text/css" href="{{asset('css/compliance.css')}}">
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