<!DOCTYPE html>
<html lang="en">
  @include('layout-head-partial')

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top @if(Auth::user() && Auth::user()->isAdmin()) admin-nav @endif">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Fahrpark</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            @if (Auth::user())
            @if (Auth::user()->isAdmin())
            <li class="@if (Request::is('users')) active @endif">
              <a title="Users" role="button" href="{{ url('users') }}">
                <i class="fa fa-user"></i>
                <span class="hidden-sm"> Users</span>
              </a>
            </li>
            @endif
            <li class="@if (Request::is('vehicle') || Request::is('vehicle/*')) active @endif dropdown">
              <a title="Vehicles" role="button" href="{{ url('vehicle') }}"><i class="fa fa-car"></i> <span class="hidden-sm">Vehicles</span></a>
            </li>
            <li @if (Request::is('journey') || Request::is('journey/*')) class="active" @endif>
              <a title="Journeys" href="{{ url('journey') }}">
                <i class="fa fa-location-arrow"></i> <span class="hidden-sm">Journeys</span>
              </a>
            </li>
            <li @if (Request::is('booking') || Request::is('booking/*')) class="active" @endif>
              <a title="Bookings" href="@if (Auth::user()->isAdmin()) {{ url('booking/admin') }} @else {{ url('booking') }} @endif">
                <i class="fa fa-ticket"></i> <span class="hidden-sm">Bookings</span>
              </a>
            </li>
            <li @if (Request::is('transactions') || Request::is('transactions/*')) class="active" @endif>
              <a title="Transactions" href="{{ url('transactions') }}">
                <i class="fa fa-money"></i>
                <span class="hidden-sm">Transactions</span>
              </a>
            </li>
            @else
            <li @if (Request::is('journey') || Request::is('journey/*')) class="active" @endif>
              <a title="Journeys" href="{{ url('journey') }}">
                <i class="fa fa-location-arrow"></i> <span class="hidden-sm">Journeys</span>
              </a>
            </li>
            @endif
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if (Auth::user())
            <p class="navbar-text hidden-sm hidden-xs">Signed in as <a href="{{ url('user/' . \Auth::user()->username) }}">{{ \Auth::user()->username }}</a></p>
            <li><a class="visible-sm-inline-block visible-xs-block" href="{{ url('user/' . \Auth::user()->username) }}">{{ \Auth::user()->username }}</a></li>
            <li><a href="/auth/logout">Logout</a></li>
            @else
            <li><a href="/auth/login">Login</a></li>
            <li><a href="/auth/register">Register</a></li>
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <div class="content" id="main-content-display">
        <!-- Errors and Status -->
        @if (count($errors) > 0 || Session::has('status') || Session::has('success') || Session::has('error') || Session::has('warning'))
        <div class="row">
          <div class="col-xs-12">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            @if (Session::has('status'))
            <div class="alert alert-info"><i class="icon fa fa-info" style="margin-right: 10px;"></i>{{ Session::get('status') }}</div>
            @endif

            @if (Session::has('success'))
            <div class="alert alert-success"><i class="icon fa fa-check" style="margin-right: 10px;"></i>{{ Session::get('success') }}</div>
            @endif

            @if (Session::has('error'))
            <div class="alert alert-danger"><i class="icon fa fa-ban" style="margin-right: 10px;"></i>{{ Session::get('error') }}</div>
            @endif

            @if (Session::has('warning'))
            <div class="alert alert-warning"><i class="icon fa fa-warning" style="margin-right: 10px;"></i>{{ Session::get('warning') }}</div>
            @endif
          </div>
        </div>
        @endif

        @yield('content')
      </div>
    </div><!-- /.container -->

    @include('layout-foot-partial')
  </body>
</html>