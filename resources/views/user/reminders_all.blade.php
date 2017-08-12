@extends("layout.navigation")

@section('content')

	<div class="row">
		<div class="col s12">

			<div class="page-header">
				<h1 class="page-name"><i class="material-icons">event_note</i> Notices</h1>
				@if(Auth::user()->isAdmin())
					<button type="button" data-target="create-reminder-first" class="btn waves-effect waves-light light-green"><i class="left material-icons">add</i>Add Notice</button>
				@endif
			</div>

			<div class="reminder-container" id="reminder-container">
				@if(!(Auth::user()->isAdmin()))
				<ul class="filter">
					<li><a href="/reminders" class="waves-effect active"><i class="material-icons all">event_note</i> All</a></li>
					<li><a href="/reminders/uncomplied" class="waves-effect"><i class="material-icons pending">assignment</i> Uncomplied</a></li>
					<li><a href="/reminders/complied" class="waves-effect"><i class="material-icons submitted">assignment_turned_in</i> Complied</a></li>
					<li><a href="/reminders/overdue" class="waves-effect"><i class="material-icons late">assignment_late</i> Overdue</a></li>
				</ul>
				@endif

				@if((count($pending) != 0) || (count($late) != 0) || (count($submitted) != 0))
				@if($pending != null)
				@forelse($pending as $reminder)
				<div class="reminder">
					<div>
						<h2 class="title" id="title"><i class="material-icons pending">assignment</i>{{$reminder->title}}</h2>
						<p class="creation date">{{date('m-d-Y', strtotime($reminder->created_at))}}</p>
						<p class="from">{{$reminder->description}}</p>
						<p class="from">Notice from <img src="{{asset($reminder->user->profile_picture)}}"> {{$reminder->user->name}} due on <span class="date">{{date('m-d-Y', strtotime($reminder->due_date))}}</span></p>
						<div class="reminder-options">
							@if(Auth::user()->isAdmin())
							<a class="waves-effect waves-light btn blue" href="/compliances/{{$reminder->id}}">Manage</a>
							<button type="button" data-target="edit-reminder" class="waves-effect waves-light btn amber edit-btn" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">edit</i>
							<button type="button" data-target="block-reminder" class="waves-effect waves-light btn grey" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">block</i></button>
							<button type="button" data-target="delete-reminder" class="waves-effect waves-light btn red delete-btn" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">delete</i></button>
							@else

							@if($reminder->checkComplied())
							<a class="waves-effect waves-light btn blue" href="/reminders/{{$reminder->id}}/complied">Comply</a>
							@elseif($reminder->checkRejected())
							<p> Rejected by {{$reminder->checkRejected()}} click <a class="waves-effect waves-light" href="/reminders/{{$reminder->id}}/complied">here</a> to reapply.</p>
							@else
							<p> Pending for Approval </p>
							@endif

							@endif
						</div>
						<p class="description hide">{{ $reminder->description }}</p>
						<p class="due_date hide">{{ date('Y-m-d', strtotime($reminder->due_date)) }}</p>

					</div>
				</div>
				@empty
				@if((count($late) == 0) AND (count($submitted) == 0))
					You haven't complied any Notices on time.
				@endif
				@endforelse
				@endif

				@if($late != null)
				@forelse($late as $reminder)
				<div class="reminder">
					<div>
						<h2 class="title"><i class="material-icons late">assignment_late</i>{{$reminder->title}}</h2>
						<p class="creation date">{{date('m-d-Y', strtotime($reminder->created_at))}}</p>
						<p class="from">{{$reminder->description}}</p>
						<p class="from">Notice from <img src="{{asset($reminder->user->profile_picture)}}"> {{$reminder->user->name}} due on <span class="date">{{date('m-d-Y', strtotime($reminder->due_date))}}</span></p>
						<div class="reminder-options">
							@if(Auth::user()->isAdmin())
							<a class="waves-effect waves-light btn blue" href="/reminders/compliances">Manage</a>
							<button type="button" data-target="edit-reminder" class="waves-effect waves-light btn amber edit-btn" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">edit</i></button>
							<button type="button" data-target="block-reminder" class="waves-effect waves-light btn grey" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">block</i></button>
							<button type="button" data-target="delete-reminder" class="waves-effect waves-light btn red delete-btn" data-reminder-id="{{ $reminder->id }}"><i class="material-icons">delete</i></button>
							@else

							@if($reminder->checkComplied())
							<a class="waves-effect waves-light btn blue" href="/reminders/{{$reminder->id}}/complied">Comply</a>
							@elseif($reminder->checkRejected())
							<p> Rejected by {{$reminder->checkRejected()}} click <a class="waves-effect waves-light" href="/reminders/{{$reminder->id}}/complied">here</a> to reapply.</p>
							@else
							<p> Pending for Approval </p>
							@endif

							@endif
						</div>
						<p class="description hide">{{ $reminder->description }}</p>
						<p class="due_date hide">{{ date('Y-m-d', strtotime($reminder->due_date)) }}</p>
					</div>
				</div>
				@empty
				@if((count($pending) == 0) AND (count($submitted) == 0))
					You complied Notices on time.
				@endif
				@endforelse
				@endif

				@if($submitted != null)
				@forelse($submitted as $reminder)
				<div class="reminder">
					<div>
						<h2 class="title"><i class="material-icons submitted">assignment_turned_in</i>{{$reminder->title}}</h2>
						<p class="from">{{$reminder->description}}</p>
						<p class="from">Notice from <img src="{{asset($reminder->user->profile_picture)}}"> {{$reminder->user->name}} due on {{date('m-d-Y', strtotime($reminder->due_date))}}</p>
					</div>

					<div class="reminder-options">
						<p> Accepted by {{$reminder->checkAccepted()}} </p>
					</div>

					<!-- <p class="creation date">{{date('m-d-Y', strtotime($reminder->created_at))}}</p> -->
					<p class="creation date">{{ Carbon\Carbon::parse($reminder->created_at)->diffForHumans() }}</p>
				</div>
				@empty
				@if((count($pending) == 0) AND (count($late) == 0))
					You haven't complied any Notices yet.
				@endif
				@endforelse
				@endif

				@else
					No notice available
				@endif

			</div>
		</div>
	</div>

	<div class="modal form-modal" id="create-reminder-first">
		<div class="modal-content">
			<h1 class="modal-header">Add Notice</h1>
			<form action="{{ route('reminders.store') }}" method="POST" role='form'>
	            {{ csrf_field() }}
				<div class="input-field">
					<input id="reminder_title" type="text" class="materialize-textarea" name="reminder_title" placeholder="Title" required="required">
					<!-- <label for="reminder_title">Notice Title</label> -->
				</div>
				<textarea placeholder="Notice description" name="reminder_description" required="required"></textarea>
				<input type="datetime-local" name="due_date" required="required" />
				<div class="radio-btns">
					<div>
						<input class="with-gap" name="reminder_target" type="radio" id="mun-radio" value="mun" checked />
					    <label for="mun-radio">Municipality</label>
					</div>
					<div>
						<input class="with-gap" name="reminder_target" type="radio" id="ind-radio" value="ind" />
					    <label for="ind-radio">Individual</label>
					</div>
				</div>
				<button class="btn waves-effect waves-light light-green"><i class="material-icons left">add</i> Add Notice</button>
			</form>
		</div>
	</div>

	<div class="modal form-modal" id="edit-reminder">
		<div class="modal-content">
			<h1 class="modal-header">Edit Notice</h1>
			<form action="" method="" id="edit_reminder_form" enctype="multipart/form-data">
				{{csrf_field()}}
				{{method_field('PUT')}}
				<div class="input-field">
					<input id="edit_reminder_title" type="text" name="edit_reminder_title" required="required" placeholder="Title">
					<!-- <label for="reminder_title">Notice Title</label> -->
				</div>
				<textarea placeholder="Reminder description" id="edit_reminder_description" name="edit_reminder_description" required="required"></textarea>
				<input type="datetime-local" name="due_date" id="edit_due_date" required="required" />

				<button type="submit" class="btn waves-effect waves-light amber"><i class="material-icons left">edit</i> Edit Notice</button>
			</form>
		</div>
	</div>

	<div class="modal block-modal" id="block-reminder">
		<div class="modal-content center">
			<h1 class="modal-header center">Are you sure you want to deactivate this Notice?</h1>
			<div class="btn-group-horiz">
				<button class="btn waves-effect waves-light grey close-delete"><i class="material-icons left">cancel</i>Cancel</button>
				<form action="" >
					<button type="submit" class="btn waves-effect waves-light red"><i class="material-icons left">block</i>Deactivate</button>
				</form>
			</div>
		</div>
	</div>

	<div class="modal delete-modal" id="delete-reminder">
		<div class="modal-content center">
			<h1 class="modal-header center">Are you sure you want to delete this Notice?</h1>
			<div class="btn-group-horiz">
				<button class="btn waves-effect waves-light grey close-delete"><i class="material-icons left">cancel</i>Cancel</button>
				<form id="delete_form" action="" method="" enctype="multipart/form-data">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<button type="submit" class="btn waves-effect waves-light red"><i class="material-icons left">delete</i>Delete</button>
				</form>
			</div>
		</div>
	</div>

	<div class="modal" id="message">
		<div class="modal-content">
			<h1 class="modal-header"></h1>
			<p></p>
		</div>
		<div class="modal-footer">
			<a href="javascript:void(0)" class="modal-action modal-close waves-effect btn-flat">OK</a>
		</div>
	</div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/reminders.css') }}">
    <style type="text/css">
    	.modal-header{
    		font-size: 1.5em;
    	}

    	#employees img{
    		height: 1em;
    		width: 1em;
    	}

		.description{
			display: none;
		}
    </style>
@endpush

@push('scripts')
	<script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.close-delete').click(function(){
			$('#delete-reminder').modal('close');
		});
		$(document).ready(function(){
			$(document).on("click", ".edit-btn", editReminder);
			$(document).off("submit", "#edit_reminder_form").on("submit", "#edit_reminder_form", updateReminder);
			$(document).on("click", ".delete-btn", deleteConfirmation);
			$(document).off("submit", "#delete_form").on("submit", "#delete_form", deleteReminder);
			var min_due = new Date();
			min_due.setDate(min_due.getDate()+2);
			$("input[type='datetime-local']").attr("min", formatDate(min_due)+"T08:00:00");
		});

		function formatDate(date){
			var d = new Date(date),
				month = '' + (d.getMonth()+1),
				day = '' + d.getDate(),
				year = '' + d.getFullYear();
			if(month.length < 2){
				month = '0' + month;
			}

			if(day.length < 2){
				day = '0'+day;
			}

			return [year, month, day].join('-');
		}

		function deleteReminder(e){
			e.preventDefault();
			var formData = new FormData($(this)[0]);
			// for(var values of formData.values()){
			// 	console.log(values);
			// }
			// console.log($(this).attr("action"));
			$("#delete-reminder").modal("close");
			$.ajax({
				url: $(this).attr("action"),
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(data){
					var reminders = "";
					var msg = data[0].split("|");
					$("#message > .modal-content > h1").html(msg[0]);
                	$("#message > .modal-content > p").html(msg[1]);
                	$("#message").modal("open");
                	var reminders = "";
                	$.each(data[1], function(key, value){
                		reminders += '<div class="reminder">'+
										'<div>'+
											'<h2 class="title" id="title"><i class="material-icons pending">assignment</i>'+value["title"]+'</h2>'+
											'<p class="from">Notice from <img src="/'+value["user_profile"]+'"> '+value["user_name"]+' due on '+value["due_date"]+'</p>'+
											'@if(Auth::user()->isAdmin())'+
											'<div class="reminder-options">'+
												'<a class="waves-effect waves-light" href="#">Manage</a>'+
												'<button type="button" data-target="edit-reminder" class="waves-effect waves-light btn amber edit-btn" data-reminder-id="'+value["id"]+'"><i class="material-icons">edit</i></button>'+
												'<button type="button" data-target="delete-reminder" class="waves-effect waves-light btn red delete-btn" data-reminder-id="'+value["id"]+'"><i class="material-icons">delete</i></button>'+
											'</div>'+
											'<p class="description hide">'+value["description"]+'</p>'+
											'<p class="due_date hide">'+value["due_date"]+'</p>'+
											'@endif'+
										'</div>'+
										'<p class="creation">'+value["created_at"]+'</p>'+
									'</div>'
                	});
                	$("#reminder-container").html(reminders);
				},
				error: function(data){
					console.log(data);
				}
			});
		}

		function deleteConfirmation(){
			$("#delete_form").attr("action", "/reminders/"+$(this).attr("data-reminder-id"));
			console.log($("#delete_form").attr("action"));
		}

		function updateReminder(e){
			e.preventDefault();
			var formData = new FormData($("#edit_reminder_form")[0]);
			$("#edit-reminder").modal("close");
			// console.log($(this).attr("data-reminder-id"));
			$.ajax({
				url: "/reminders/"+$(this).attr("data-reminder-id"),
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function(data){
					console.log(data);
					var reminders = "";
					var msg = data[0].split("|");
					$("#message > .modal-content > h1").html(msg[0]);
                	$("#message > .modal-content > p").html(msg[1]);
                	$("#message").modal("open");
                	var reminders = "";
                	$.each(data[1], function(key, value){
                		reminders += '<div class="reminder">'+
										'<div>'+
											'<h2 class="title" id="title"><i class="material-icons pending">assignment</i>'+value["title"]+'</h2>'+
											'<p class="from">Notice from <img src="/'+value["user_profile"]+'"> '+value["user_name"]+' due on '+value["due_date"]+'</p>'+
											'@if(Auth::user()->isAdmin())'+
											'<div class="reminder-options">'+
												'<a class="waves-effect waves-light" href="#">Manage</a>'+
												'<button type="button" data-target="edit-reminder" class="waves-effect waves-light btn amber edit-btn" data-reminder-id="'+value["id"]+'"><i class="material-icons">edit</i></button>'+
												'<button type="button" data-target="delete-reminder" class="waves-effect waves-light btn red delete-btn" data-reminder-id="'+value["id"]+'"><i class="material-icons">delete</i></button>'+
											'</div>'+
											'<p class="description hide">'+value["description"]+'</p>'+
											'<p class="due_date hide">'+value["due_date"]+'</p>'+
											'@endif'+
										'</div>'+
										'<p class="creation">'+value["created_at"]+'</p>'+
									'</div>'
                	});
                	$("#reminder-container").html(reminders);
				},
				error: function(data){
					console.log(data);
				}
			});
		}

		function editReminder(){
			$("#edit_reminder_title").attr("value", $("#title").text().replace('assignment',''));
			$("#edit_reminder_form").attr("data-reminder-id", $(this).attr("data-reminder-id"));
			$("#edit_reminder_description").html($(this).parent().siblings(".description")[0].innerHTML);
			$("#edit_due_date").attr("value", $(this).parent().siblings(".due_date").text());
			$("label[for='edit_reminder_title']").addClass("active");
			$("label[for='edit_reminder_description']").addClass("active");
			$("label[for='edit_due_date']").addClass("active");
		}
	</script>
@endpush
