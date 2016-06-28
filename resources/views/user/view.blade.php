@extends('layout')

<?php
  $isViewingMyAccount = Auth::user() && $user->id == Auth::user()->id;
  $isAdmin = Auth::user() && Auth::user()->isAdmin();
?>

@section('page-title')
  @if ($isViewingMyAccount)
    My Account
  @else
    {{ $user->username }}'s Account
  @endif
@endsection

@section('content')
@if ($isViewingMyAccount)
<h1>My Account</h1>
@else
<h1>{{ $user->username }}'s Account</h1>
@endif
<br />
@if ($isViewingMyAccount)
<h3>Change Password</h3>
<form class="form-horizontal" role="form" method="POST" action="{{ url('user/' . $user->username . '/changePassword') }}">
  {!! csrf_field() !!}
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label">Current Password: </label>
    <div class="col-sm-10"><input class="form-control" type="password" name="password" /></div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label">New Password: </label>
    <div class="col-sm-10"><input class="form-control" type="password" name="new_password" /></div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label">Confirm Password: </label>
    <div class="col-sm-10"><input class="form-control" type="password" name="new_password_confirmation" /></div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary" type="submit">Change Password</button></div>
  </div>
</form>
@endif
<h3>User Profile</h3>
@if ($isViewingMyAccount || $isAdmin)
<form class="form-horizontal" role="form" method="POST" action="{{ url('user/' . $user->username . '/updateProfile') }}">
  {!! csrf_field() !!}
  @endif
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label" for="">Full Name: </label>
    <div class="col-sm-10">
      @if ($isViewingMyAccount || $isAdmin)
      <input class="form-control" type="text" name="fullname" value="{{ $user->fullname }}" />
      @else
      <p class="form-control-static">{{ $user->fullname }}</p>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label" for="">Phone: </label>
    <div class="col-sm-10">
      @if ($isViewingMyAccount || $isAdmin)
      <input class="form-control" type="text" name="phone" value="{{ $user->phone }}" />
      @else
      <p class="form-control-static">{{ $user->phone }}</p>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label" for="">Last Login: </label>
    <div class="col-sm-10"><p class="form-control-static"><?php echo ($user->lastLogin=="0000-00-00 00:00:00") ? "Never" : $user->lastLogin; ?></p></div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 col-md-2 control-label" for="">Registered On: </label>
    <div class="col-sm-10"><p class="form-control-static">{{ $user->createdAt }}</p></div>
  </div>
  @if ($isAdmin)
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <label class="checkbox-inline">
        <input type="checkbox" id="isAdmin" name="isAdmin" @if ($user->isAdmin) checked @endif />
        Grant administrative privileges
      </label>
    </div>
  </div>
  @endif
  @if ($isViewingMyAccount || $isAdmin)
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary" type="submit">Update Profile</button></div>
  </div>
</form>
@endif

@if ($isAdmin)
<h3>Delete User</h3>
<div class="alert alert-danger" style="padding: 10px; text-align: center;">
  <p style="font-weight: bold;">DELETING A USER IS IRREVERSIBLE. ALL VEHICLES, BOOKINGS AND TRANSACTIONS RELATED TO THE USER WILL BE OBLITERATED TOO.<br>PROCEED WITH CAUTION.</p><br />
  <button class="btn btn-danger" style="font-weight: bold;" data-toggle="modal" data-target="#confirmModel"><i class="fa fa-exclamation-triangle"></i> DELETE USER {{ $user->username }} <i class="fa fa-exclamation-triangle"></i></button>
</div>

<div class="modal fade" id="confirmModel" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUserModal">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ url('user/' . $user->username . '/deleteUser') }}">
    {!! csrf_field() !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
      </div>
      <div class="modal-body">
        Once deleted the user will be completely gone from the system. Continue?
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Bye-bye {{ $user->username }}</button>
      </div>
    </div>
    </form>
  </div>
</div>
@endif
@endsection