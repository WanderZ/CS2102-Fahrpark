@extends('layout')

@section('page-title')
Login
@endsection

@section('content')
<h1> Fahrpark User Login <i class="fa fa-lock"></i></h1> <br/>
<form class="form-horizontal" method="POST" action="/auth/login" role="form">
  {!! csrf_field() !!}

  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Username</label>
    <div class="col-sm-10">
      <input class="form-control" id="username" type="text" name="username" value="{{ old('username') }}" autofocus>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="password">Password</label>
    <div class="col-sm-10">
      <input class="form-control" id="password" type="password" name="password" id="password">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button class="btn btn-primary" type="submit">Login</button>
    </div>
  </div>
</form>
@endsection