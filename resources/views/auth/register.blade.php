@extends('layout')

@section('page-title')
Register
@endsection

@section('content')
<h1> Fahrpark User Registration </h1><br />
<form class="form-horizontal" method="POST" action="/auth/register" role="form">
  {!! csrf_field() !!}

  <div class="form-group">
    <label class="col-sm-2 control-label" for="">Name</label>
    <div class="col-sm-10">
      <input class="form-control" id="" type="text" name="fullname" value="{{ old('fullname') }}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="">Username</label>
    <div class="col-sm-10">
      <input class="form-control" id="" type="text" name="username" value="{{ old('username') }}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="">Password</label>
    <div class="col-sm-10">
      <input class="form-control" id="" type="password" name="password">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="">Confirm Password</label>
    <div class="col-sm-10">
      <input class="form-control" id="" type="password" name="password_confirmation">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="">Phone</label>
    <div class="col-sm-10">
      <input class="form-control" id="" type="text" name="phone">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button class="btn btn-primary" type="submit">Register</button>
    </div>
  </div>
</form>
@endsection