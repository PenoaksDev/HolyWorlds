<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Holy Worlds - @yield('title')</title>

    <link href="{{ elixir('css/rna.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

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

	<script src="https://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script src="/js/jquery.push-service.js"></script>
</head>
<body class="@yield('body_class')">
    <div id="top-bar" class="navbar-fixed">
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <a href="{{ url('/') }}" class="brand-logo left">
                        <img src="{{ url('images/logo.png') }}" alt="[RNA]">
                    </a>
                    <a href="#" data-activates="mobile-links" class="button-collapse right"><i class="material-icons">menu</i></a>
                    <ul class="side-nav" id="mobile-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('forum') }}">Forum</a></li>
                        <!-- <li><a href="{{ url('events') }}">Events</a></li> -->
                        @can('viewCharacters')
                            <li><a href="{{ url('characters') }}">Characters</a></li>
                        @endcan
                        <li><a href="{{ url('gallery') }}">Gallery</a></li>
                        @if (Auth::guest())
                            <li><a href="{{ url('auth/login') }}">Login</a></li>
                            <li><a href="{{ url('auth/register') }}">Register</a></li>
                        @else
                            @can('admin')
                                <li><a href="{{ url('admin') }}">Admin</a></li>
                            @endcan
                            <li>
                                <a href="{{ url('account/notifications') }}">
                                    Notifications
                                    @if (Auth::user()->countNotificationsNotRead() > 0)
                                        <span class="deep-orange accent-4 badge">{{ Auth::user()->countNotificationsNotRead() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ url('account/profile') }}">View profile</a></li>
                            <li><a href="{{ url('account/profile/edit') }}">Edit profile</a></li>
                            <li><a href="{{ url('account/settings') }}">Account settings</a></li>
                            <li><a href="{{ url('auth/logout') }}">Log out ({{ Auth::user()->name }})</a></li>
                        @endif
                    </ul>
                    <ul class="right hide-on-med-and-down">
                        <li><a class="dropdown-button" href="{{ url('forum') }}" data-activates="forum-links" data-beloworigin="true" data-constrainwidth="false" data-hover="true">Forum&nbsp; <i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                        <!-- <li><a href="{{ url('events') }}">Events</a></li> -->
                        @can('viewCharacters')
                            <li><a href="{{ url('characters') }}">Characters</a></li>
                        @endcan
                        <li><a href="{{ url('gallery') }}">Gallery</a></li>
                        @if (Auth::guest())
                            <li><a href="{{ url('auth/login') }}">Login</a></li>
                            <li><a href="{{ url('auth/register') }}">Register</a></li>
                        @else
                            @can('admin')
                                <li><a class="dropdown-button" href="{{ url('admin') }}" data-activates="admin-links" data-beloworigin="true" data-constrainwidth="false" data-hover="true">Admin&nbsp; <i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                            @endcan
                            <li>
                                <a class="dropdown-button" href="#!" data-activates="user-links" data-beloworigin="true" data-constrainwidth="false" data-hover="true">
                                    @include('user.partials.avatar', ['user' => Auth::user(), 'class' => 'tiny circular'])
                                    &nbsp;Hello, <strong>{{ Auth::user()->name }}</strong>!&nbsp;
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    @if (Auth::user()->countNotificationsNotRead() > 0)
                                        <span class="deep-orange accent-4 new badge">{{ Auth::user()->countNotificationsNotRead() }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    </ul>

                    <ul id="forum-links" class="dropdown-content">
                        <li><a href="{{ url('forum') }}">Forum index</a></li>
                        <li><a href="{{ url('forum/new') }}">New &amp; updated</a></li>
                    </ul>
                    @if (Auth::check())
                        @can('admin')
                            <ul id="admin-links" class="dropdown-content">
                                <li><a href="{{ url('admin/article') }}">Articles</a></li>
                                <!-- <li><a href="{{ url('admin/event') }}">Events</a></li> -->
                                <li><a href="{{ url('admin/forum/category') }}">Forum Categories</a></li>
                                <li><a href="{{ url('admin/users') }}">Users</a></li>
                            </ul>
                        @endcan
                        <ul id="user-links" class="dropdown-content">
                            <li>
                                <a href="{{ url('account/notifications') }}">
                                    Notifications
                                    @if (Auth::user()->countNotificationsNotRead() > 0)
                                        <span class="deep-orange accent-4 new badge">{{ Auth::user()->countNotificationsNotRead() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ url('account/profile') }}">View profile</a></li>
                            <li><a href="{{ url('account/profile/edit') }}">Edit profile</a></li>
                            <li><a href="{{ url('account/settings') }}">Account settings</a></li>
                            <li><a href="{{ url('auth/logout') }}">Log out</a></li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>
    </div>

    <div id="main">
        <div id="splash">
            <div class="container">
                <h1>@yield('title')</h1>
                <p>@yield('subtitle')</p>
                @yield('before_content')
            </div>
        </div>
        <div id="content">
            <div class="container">
                <div id="inner" class="z-depth-1">
                    @if (array_key_exists('breadcrumbs', View::getSections()))
                        <nav class="breadcrumbs z-depth-0">
                            <div class="nav-wrapper">
                                <div class="col s12">
                                    <a href="{{ url('/') }}" class="breadcrumb">Home</a>
                                    @section('breadcrumbs')
                                    @show
                                </div>
                            </div>
                        </nav>
                    @endif

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
                </div>

                @yield('after_content')
            </div>
            <div id="footer">
                <div class="container">
                    <div class="inner">
                        All times are displayed in UTC.
                    </div>
                </div>
            </div>
			
			<div id="chat">
				
				<div id="chat_header">
					<span id="chat_header_title">Chat</span>
					<span id="chat_header_open" class="fa fa-comments fa-3x"></span>
				</div>
				
				<div id="chat_box">
					<div id="chat_box_content"></div>
					<input type="text" id="chat_input" placeholder="chat" />
				</div>
			</div>
        </div>
    </div>

    <script src="{{ elixir('js/rna.js') }}"></script>
	<script src="{{ URL::asset('js/init.js') }}"></script>
    @yield('bottom')
</body>
</html>
