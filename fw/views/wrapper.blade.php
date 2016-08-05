<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Holy Worlds<?php if ( !empty( $__env->yieldContent( 'title' ) ) )
			echo ' - ' . $__env->yieldContent( 'title' ); ?></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" integrity="sha384-MI32KR77SgI9QAPUs+6R7leEOwtop70UsjEtFEezfKnMjXWx15NENsZpfDgq8m8S" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600%7CArvo:700" integrity="sha384-47bvWTKZJpcIOn6FWukdVDglecWZ3LNjx9VO5WMFgg0806mbCW1iPoV2XCgTJGpm" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/dark-hive/jquery-ui.css" integrity="sha384-3NnLWQRgj7gnx73m9m1/w3QauUEzv26OgowZBfureQ7rFPeBeKl0X7DiytyrlfOK" crossorigin="anonymous">
	<link href="/css/style.css{{-- URL::asset('css/style.css') --}}" rel="stylesheet" />

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

	@yield('head')

	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#3a3c46">
	<meta name="msapplication-TileColor" content="#dd2c00">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
<nav id="mainmenu" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="glyphicon glyphicon-th-list"></span> Menu
			</button>
			<a style="margin: 8px 0 8px 15px; padding: 0; height: 40px; color: #fff;" class="navbar-brand visible-xs-block" href="{{ url('/') }}">
				{{-- <img id="brand1" src="{{ URL::asset('images/brand.png') }}" alt="Holy Worlds Brand" style="height: 40px; display: none;" /> --}}
				<span><img id="brand" src="{{ URL::asset('images/brand.png') }}" alt="Holy Worlds Brand" style="height: 40px;" /> Holy Worlds</span>
			</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="{{ url('/') }}"><span class="glyphicon glyphicon-home"></span> Home</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-book"></span> Forum
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{{ url('forum') }}"><span class="glyphicon glyphicon-book"></span> Forum Index</a>
						</li>
						<li>
							<a href="{{ url('forum') }}"><span class="glyphicon glyphicon-book"></span> New &amp; Updated</a>
						</li>
					</ul>
				</li>
				{{-- <li><a href="{{ url('gallery') }}"><span class="glyphicon glyphicon-picture"></span> Gallery</a></li> --}}
				<li><a href="{{ URL::to('pages/about') }}"><span class="glyphicon glyphicon-heart"></span> About</a>
				</li>
				<li><a href="{{ URL::to('pages/contact') }}"><span class="glyphicon glyphicon-envelope"></span> Contact</a>
				</li>
			</ul>
			<ul style="margin-right: 15px;" class="nav navbar-nav navbar-right">
				@if ( Acct::isGuest() )
					<li><p class="navbar-text">Welcome Guest</p></li>
					<li>
						<a href="{{ URL::route( 'login' ) }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Sign In</a>
					</li>
					<li>
						<a href="{{ URL::route( 'register' ) }}"><i class="fa fa-user-plus" aria-hidden="true"></i> Register</a>
					</li>
				@else
					<li class="dropdown"> <!-- {{ url('account/notifications') }} -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="btnNotice">
							<i class="fa fa-bell-o" aria-hidden="true"></i>
							<span class="badge">{{-- Acct::acct()->countNotificationsNotRead() --}}</span>
						</a>
						<div class="dropdown-menu">
							<center><p style="color: #fff;">No Notifications</p></center>
						</div>
					</li>
					<li>
						<a href="{{ url('messages') }}" id="btnChat">
							<i class="fa fa-comments-o" aria-hidden="true"></i>
							<span class="badge">0</span>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							@include('user.partials.avatar', ['user' => Acct::acct(), 'class' => 'img-circle'])
							Welcome {{ Acct::getDisplayName() }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="{{ url('account/profile') }}"><i class="fa fa-tachometer" aria-hidden="true"></i> My Dashboard</a>
							</li>
							@if ( Acct::isAdmin() )
								<li>
									<a href="{{ url('admin') }}"><i class="fa fa-lock" aria-hidden="true"></i> Administrator</a>
								</li>
							@endif
							<li role="separator" class="divider"></li>
							<li>
								<a class="noAjax" href="{{ URL::route( 'logout' ) }}"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out</a>
							</li>
						</ul>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>

<div id="wrapper">
	<div id="wrapper-inner">
		@if ( !Request::ajax() )
			<div class="overlay"></div>

			<div class="container">
				<div class="header-container">
					<div class="header hidden-xs">
						<a href="{{ url('/') }}"><img src="{{ url('images/logo.png') }}" style="height: 128px;" /></a>
					<!-- <h1><a href="{{ url('/') }}">Holy Worlds</a></h1> -->
						<p>Community of Christ-centered Creativity</p>
					</div>
				</div>
			</div>
		@endif

		<div class="container" id="pageBody">
			@yield('before_content')
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="pull-left" style="margin: 5px 0; padding: 0;"><?php if ( empty( $__env->yieldContent( 'pagetitle' ) ) )
							echo $__env->yieldContent( 'title' );
						else echo $__env->yieldContent( 'pagetitle' ); ?></h3>
					@if (false && array_key_exists('breadcrumbs', View::getSections()))
						<ol class="hidden-xs hidden-sm breadcrumb pull-right" style="margin: 0;">
							<li><a href="{{ url('/') }}">Home</a></li>
							@section('breadcrumbs')
							@show
						</ol>
					@endif
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					{{-- Notification::showAll() --}}
					@if (isset($errors) && count($errors) > 0)
						@foreach ($errors->all() as $error)
							<p class="alert alert-danger">{{ $error }}</p>
						@endforeach
					@endif
					@yield('content')
				</div>
			</div>
			@yield('after_content')
			@yield('bottom')
		</div>

		@if ( !Request::ajax() )
			<div class="container">
				<hr />
				<footer>
					<p class="pull-right"><a href="{{ url("pages/privacy") }}">Privacy</a> &#8226;
						<a href="{{ url("pages/terms") }}">Terms</a> &#8226;
						<a href="javascript: $(this).scrollTop(0);">Back to top</a></p>
					<p>&copy; 2016 Holy Worlds &#8226; A subsidiary of
						<a target="_blank" href="http://penoaks.com">Penoaks Publishing Co.</a> &#8226; All rights reserved
					</p>
				</footer>
			</div>
		@endif

		{{-- Javascript --}}
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.0.0/imagesloaded.pkgd.min.js" integrity="sha384-lo1387qiXiWtLqo43qlYIRguUcLZVLBF6O4zWv/qyj8GbS5fQ09Jn7HEdPw/QnXt" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
		<script type="text/javascript" src="{{ URL::asset('js/history/scripts/bundled/html4+html5/jquery.history.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('js/jquery.push-service.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('js/global.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('js/init-chat.js') }}"></script>

		{{-- TODO Implement Google Analytics --}}
		@if ( !Request::ajax() )
			<script type="text/javascript">
				$( document ).ready(
						function()
						{
							var lastHref = null;

							History.Adapter.bind(
									window, 'statechange', function()
									{
										navigate( History.getState().url, window.location );
									}
							);

							onClickEvent = function( e )
							{
								var href = $( this ).attr( 'href' );
								var referrer = '' + window.location;
								e.preventDefault();
								navigate( href, referrer );
							};

							$( 'a:not(.noAjax):not([class^="phpdebugbar"]):not([href^="javascript"])[rel!="external"][target!="_blank"][href!="#"], .ajaxLink' )
									.click( onClickEvent );

							function navigate( href, referrer )
							{
								// Ignore empty hash and repeated navigation from state changes
								if( href == "#" || href == lastHref )
									return;

								navigating = true;

								hideMenu();
								$( document ).scrollTop( 0 );

								// Show loading message
								$( '.overlay' ).addClass( 'loading' );
								lastHref = href;

								// DO AJAX
								$.ajax(
										{
											url: href
										}
								).done(
										function( html, textStatus, jqXHR )
										{
											var title = null;

											// Reference: https://github.com/browserstate/ajaxify
											// https://github.com/browserstate/history.js

											html = html.replace( /<html( .+?)?>/gi, '<div$1>' );
											html = html.replace( /<head( .+?)?>/gi, '<div rel="head"$1>' );
											html = html.replace( /<body( .+?)?>/gi, '<div rel="body"$1>' );
											html = html.replace( /<\/html>/gi, '</div>' );
											html = html.replace( /<\/head>/gi, '</div>' );
											html = html.replace( /<\/body>/gi, '</div>' );

											html = $( $.parseHTML( html ) );
											head = $( 'div[rel=head]', html );
											body = $( 'div[rel=body]', html );

											loaded = [];
											$( 'script' ).each(
													function()
													{
														if( $( this ).attr( 'src' ) !== undefined )
														{
															loaded.push( $( this ).attr( 'src' ) );
														}
													}
											);
											$( 'head link' ).each(
													function()
													{
														loaded.push( $( this ).attr( 'href' ) );
													}
											);

											if( head !== undefined )
											{
												head.find( 'script' ).each(
														function()
														{
															if( $( this )
																			.attr( 'src' ) !== undefined && $.inArray(
																			$( this )
																					.attr( 'src' ), loaded
																	) < 0 )
															{
																if( console )
																{
																	console.info(
																			"Loading SCRIPT " + $( this )
																					.attr( 'src' )
																	)
																}
																$( 'head' ).append( this );
															}
														}
												);
												head.find( 'link' ).each(
														function()
														{
															if( $( this )
																			.attr( 'href' ) !== undefined && $.inArray(
																			$( this )
																					.attr( 'href' ), loaded
																	) < 0 )
															{
																if( console )
																{
																	console.info(
																			"Loading CSS " + $( this )
																					.attr( 'href' )
																	)
																}
																$( 'head' ).append( this );
															}
														}
												);

												title = head.find( 'title' ).text();
											}

											body.find( 'script' ).each(
													function()
													{
														if( $( this )
																		.attr( 'src' ) !== undefined && $.inArray(
																		$( this )
																				.attr( 'src' ), loaded
																) < 0 )
														{
															if( console )
															{
																console.info(
																		"Loading SCRIPT " + $( this )
																				.attr( 'src' )
																)
															}
															$( 'head' ).append( this );
														}
													}
											);
											html.find( 'style' ).each(
													function()
													{
														$( 'body' ).append( this );
													}
											);

											History.pushState( null, title, href );

											$( '#pageBody' )
													.html(
															$( '#pageBody', html ).exists() ? $( '#pageBody', html )
																	.html() : $( html ).html()
													);
											$( '#pageBody' )
													.find( 'a:not(.noAjax):not([class^="phpdebugbar"]):not([href^="javascript"])[rel!="external"][target!="_blank"][href!="#"], .ajaxLink' )
													.click( onClickEvent );
											$( '.overlay' ).removeClass( 'loading' );

											if( console )
											{
												console.info( "Successfully AJAX navigated to " + href );
											}

											if( _gaq )
											{
												// Send ajax analytic data to Google
												_gaq.push( ['_setReferrerOverride', referrer] );
												_gaq.push( ['_trackPageview', href] );
											}
										}
								).fail(
										function( jqXHR, textStatus, errorThrown )
										{
											// TODO Display AJAX error page
											// Temp! Forcefully redirect so user can see exception page.
											window.location = href;
										}
								);
							}

							function hideMenu()
							{
								$( '#wrapper-inner, .navbar-collapse' ).removeClass( 'toggled' );
								$( '.overlay' ).removeClass( 'visible' );
							}

							$( '[data-toggle="offcanvas"]' ).click(
									function()
									{
										$( '#wrapper-inner, .navbar-collapse' ).toggleClass( 'toggled' );
										$( '.overlay' ).toggleClass( 'visible' );
									}
							);
						}
				);
			</script>
		@endif

		{{-- @include('messages.chat') --}}
	</div>
</div>
</body>
</html>
