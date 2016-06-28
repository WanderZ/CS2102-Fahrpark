@extends('layout')

@section('page-title')
View Journeys Booking Requests
@endsection

@section('content')
<h1> Journey Booking Requests </h1><br />

@if (!count($journeys))
<p> No journeys have been requested yet. </p>
<p> Do you wish to signup for a new Journey Now?
  <a href="/journey/">Sign up Now</a>
</p>
@else

<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <tr>
        <th>Vehicle</th>
        <th>Route</th>
        <th>Pickup &amp; ETA</th>
        <th>Cost</th>
        <th>Status</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      @foreach($journeys as $journey)
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
        <td>{{$journey->status}}</td>
        <td><a href="/journey/view/{{$journey->journey_id}}">More Details</a></td>
      </tr>
      @endforeach
  </table>
</div>
@endif
@endsection