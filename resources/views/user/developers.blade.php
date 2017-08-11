 <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('images/kajon.ico') }}" type="image/x-icon">


    <title>Kajon</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize_icons.css')}} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/materialize.min.css')}} " rel="stylesheet" type="text/css">

    <link href="{{ asset('css/navigation.css') }}" rel="stylesheet" type="text/css">

    <style type="text/css">
    	body{
			margin: 0;
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 5vh;
    	}

    	.developers{
    		display: flex;
    		flex-wrap: wrap;
    		justify-content: center;
    		flex-direction: column;
    	}

    	.developers > h1{
    		width: 100%;
    		font-size: 2em;
    		text-align: center;
    		text-transform: uppercase;
    		margin-bottom: 1.5em;
    		margin-top: 1em;
    	}

    	@media screen and (min-width: 600px) {
    		.developers{
    			flex-direction: row;
    		}
    		.developers > h1{
	    		margin-top: 0;
	    		font-size: 3em;
    		}
    	}

    	.developers > article{
    		padding: 1rem;
    		box-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
    		margin: 0.5rem;
    		width: 250px;
    		max-width: 250px;
    	}

    	.developers img{
    		height: 15rem;
    		width: 15rem;
    		display: block;
    		margin: auto;
    		border-radius: 3px;
    	}

    	.developers > article > h2{
    		font-size: 1.5rem;
    		text-align: center;
    	}

    	.developers > article > h3{
    		font-size: 1em;
    		font-style: italic;
    		text-align: center;
    		color: #0987d0;
    		margin: 0.25em;
    	}

    </style>

</head>
<body>

	<div>
		<section class="developers">
			<h1>Developers</h1>
			<article>
				<img src="{{asset('images/gregg.png')}}" alt="Gregg's photo">
				<h2>Gregg Marrion Icay</h2>
				<h3>Project Manager</h3>
				<h3>Back-end Developer</h3>
			</article>
			<article>
				<img src="{{asset('images/allyn.png')}}" alt="Allyn's photo">
				<h2>Allyn Joy Calcaben</h2>
				<h3>Full-stack Developer</h3>
			</article>
			<article>
				<img src="{{asset('images/lincy.png')}}" alt="Lincy's photo">
				<h2>Lincy Legada</h2>
				<h3>Back-end Developer</h3>
			</article>
			<article>
				<img src="{{asset('images/clyde.png')}}" alt="Clyde's photo">
				<h2>Clyde Joshua Delgado</h2>
				<h3>UX/UI Designer</h3>
				<h3>Front-end Developer</h3>
			</article>
		</section>
	</div>

	<script src="{{ asset('js/jquery.min.js') }}"></script>

</body>
</html>