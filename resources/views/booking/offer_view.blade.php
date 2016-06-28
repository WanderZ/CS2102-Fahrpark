<?php
$isAdmin = Auth::user() && Auth::user()->isAdmin();
?>
@extends('layout')

@section('page-title')
View Offer of Journey
@endsection

@section('content')
<h1>Booking Requests</h1><br />
@if (!$journey)
<p> No such journey have been registered yet. </p>
<p> Do you wish to create a new Journey Now?
  <a href="/journey/create">Create Now</a>
</p>
@else
<h2>Journey Details </h2>
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Vehicle</th>
        <th>Route</th>
        <th>Pickup &amp; ETA</th>
        <th>Cost</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{$journey->vehicle}}</td>
        <td>
          <strong style="width: 25px;">Start</strong>: {{$journey->start}}
          <br /> to <br />
          <strong style="width: 25px;">End</strong>: {{$journey->end}}
        </td>
        <td>
          {{$journey->departureDatetime}}
          <br />to<br />
          {{$journey->arrivalDatetime}}
        </td>
        <td>&#36;{{$journey->cost}}</td>
        <td>
          <a href="/journey/view/{{$journey->id}}">More Details</a></td>
      </tr>
  </table>
</div>
<h2>Passenger Requests</h2>
<div class="row">
  <div class"table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Time requested</th>
          <th>Username</th>
          <th>Full Name</th>
          <th>Contact Number</th>
          <th>Remarks</th>
          <th>
            @if($isAdmin)
            &nbsp;
            @else
            Status
            @endif
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $booking)
        <tr>
          <td>{{$booking->createdAt}}</td>
          <td>{{$booking->username}}</td>
          <td>{{$booking->fullname}}</td>
          <td>{{$booking->phone}}</td>
          <td>{{$booking->remarks}}</td>
          <td>
            @if($isAdmin)
            <form class="form-inline" role="form">
              <a role="button" class="btn btn-info" href="/booking/edit/{{$booking->id}}">Update</a>
              <a role="button" class="btn btn-danger" href="/booking/delete/{{$booking->id}}">Delete</a>
            </form>
            @elseif($booking->status == 'PENDING')
            <form class="form-inline" role="form">
              @if ($canAccept)
              <a role="button" class="btn btn-success" href="/booking/offer_view/accept/{{$journey->id}},{{$booking->id}}">Accept</a>
              @endif
              <a role="button" class="btn btn-danger" href="/booking/offer_view/reject/{{$journey->id}},{{$booking->id}}">Reject</a>
            </form>
            @else
            {{$booking->status}}
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection