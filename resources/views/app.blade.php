<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Holy Worlds @if ( !empty( $__env->yieldContent('title') ) )- @yield('title') @endif</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" integrity="sha384-MI32KR77SgI9QAPUs+6R7leEOwtop70UsjEtFEezfKnMjXWx15NENsZpfDgq8m8S" crossorigin="anonymous" />
        <link rel="stylesheet" src="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600%7CArvo:700" integrity="sha384-47bvWTKZJpcIOn6FWukdVDglecWZ3LNjx9VO5WMFgg0806mbCW1iPoV2XCgTJGpm" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/dark-hive/jquery-ui.css" integrity="sha384-3NnLWQRgj7gnx73m9m1/w3QauUEzv26OgowZBfureQ7rFPeBeKl0X7DiytyrlfOK" crossorigin="anonymous">
        <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet" />

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
    <body class="@yield('body_class')">
        <div class="container">
            <div class="header-container">
        		<div class="header">
        			<a href="{{ url('/') }}"><img src="{{ url('images/logo.png') }}" style="height: 128px;" /></a>
        			<!-- <h1><a href="{{ url('/') }}">Holy Worlds</a></h1> -->
        			<p>Community of Christ-centered Creativity</p>
        		</div>
        	</div>
            <nav id="mainmenu" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only"><span class="glyphicon glyphicon-th-list"></span> Menu</span>
                        </button>
                        <!-- <a style="padding: 5px 15px;" class="navbar-brand" href="{{ url('/') }}">
                            <img id="brand1" src="brand1.png" alt="logo" style="height: 40px; display: none;" />
                            <img id="brand2" src="brand2.png" alt="logo" style="height: 40px;" />
                        </a> -->
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-book"></span> Forum <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('forum') }}"><span class="glyphicon glyphicon-book"></span> Forum Index</a></li>
                                    <li><a href="{{ url('forum') }}"><span class="glyphicon glyphicon-book"></span> New &amp; Updated</a></li>
                                </ul>
                            </li>
                            @can('viewCharacters')
                                <li><a href="{{ url('characters') }}"><span class="glyphicon glyphicon-heart"></span> Characters</a></li>
                            @endcan
                            <li><a href="{{ url('gallery') }}"><span class="glyphicon glyphicon-picture"></span> Gallery</a></li>
                            <li><a href="{{ url('pages/about') }}"><span class="glyphicon glyphicon-heart"></span> About</a></li>
                            <li><a href="{{ url('pages/contact') }}"><span class="glyphicon glyphicon-envelope"></span> Contact</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            @if (Auth::guest())
                                <li><p class="navbar-text">Welcome Guest</p></li>
                                <li><a href="{{ url('auth/login') }}"><span class="glyphicon glyphicon-log-in"></span> Sign In</a></li>
                                <li><a href="{{ url('auth/register') }}"><span class="glyphicon glyphicon-lock"></span> Register</a></li>
                            @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        @include('user.partials.avatar', ['user' => Auth::user()])
                                        Welcome {{ Auth::user()->name }}
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ url('account/notifications') }}">
                                                Notifications
                                                @if (Auth::user()->countNotificationsNotRead() > 0)
                                                    <span class="badge">{{ Auth::user()->countNotificationsNotRead() }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li><a href="{{ url('account/settings') }}">Account settings</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ url('account/profile') }}"><span class="glyphicon glyphicon-user"></span> My Account</a></li>
                                        @if (Auth::user()->hasPermission("sys.admin"))
                                            <li><a href="{{ url('admin') }}"><span class="glyphicon glyphicon-user"></span> Admin Dashboard</a></li>
                                        @endif
                                    </ul>
                                </li>
                                <li><a href="{{ url('auth/logout') }}"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @yield('before_content')
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="pull-left" style="margin: 5px 0; padding: 0;">@if ( empty( $__env->yieldContent('pagetitle') ) ) @yield('title') @else @yield('pagetitle') @endif</h3>
                            @if (array_key_exists('breadcrumbs', View::getSections()))
                            <ol class="breadcrumb pull-right" style="margin: 0;">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                @section('breadcrumbs')
                                @show
                            </ol>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            {!! Notification::showAll() !!}
                            @if (isset($errors) && count($errors) > 0)
                                <div class="alert error">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @yield('content')
                            @yield('after_content')
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr />
            <footer>
            	<p class="pull-right"><a href="{{ url("pages/privacy") }}">Privacy</a> &#8226; <a href="{{ url("pages/terms") }}">Terms</a> &#8226; <a href="javascript: $(this).scrollTop(0);">Back to top</a></p>
            	<p>&copy; 2016 Holy Worlds &#8226; A subsidiary of <a target="_blank" href="http://penoaks.com">Penoaks Publishing Co.</a> &#8226; All rights reserved</p>
            </footer>
        </div>

        @include('messages.chat')
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.0.0/imagesloaded.pkgd.min.js"></script>
        <script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
        <script src="{{ URL::asset('js/jquery.push-service.js') }}"></script>
    	<script src="{{ URL::asset('js/init.js') }}"></script>
        @yield('bottom')
    </body>
</html>
