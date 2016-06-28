@extends('layout')

@section('page-title')
Edit Your Booking
@endsection

@section('content')
<h1>Edit Booking</h1><br />
<form class="form-horizontal" id="edit" role="form" method="POST" action="/booking/edit">
  {!! csrf_field() !!}
  <input type="hidden" name="booking_id" value="{{$booking->id}}">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="remarks">Journey ID</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="journey_id" value="{{$booking->journey_id}}">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="remarks">Username</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="username" value="{{$booking->username}}">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="status">Status</label>
    <div class="col-sm-10">
      <select class="form-control" id="status" name="status" form="edit">
        <option value="{{$booking->status}}" selected="selected"> {{$booking->status}}</option>
        @if($booking->status !== 'ACCEPT')
        <option value="ACCEPT">ACCEPT</option>
        @endif
        @if($booking->status !== 'REJECT')
        <option value="REJECT">REJECT</option>
        @endif
        @if($booking->status !== 'PENDING')
        <option value="PENDING">PENDING</option>
        @endif
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="remarks">Remarks</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="remarks" value="{{$booking->remarks}}">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary" type="submit">Update Booking</button></div>
  </div>
</form>
@endsection