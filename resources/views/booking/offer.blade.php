@extends('layout')

@section('page-title')
View Offered Journeys | Incoming Booking Requests
@endsection

@section('content')
<h1> Offered Journeys | Incoming Booking Requests </h1>

@if (!count($journeys))
  <p> There are no Bookings now. </p>
  <p> Do you wish to create a new Journey Now?
    <a href="/journey/create">Create Journey Now</a>
  </p>
<p> You can also request to join in others' journeys via the <a href="/journey">Journeys</a> Page </p>
@else
<div class="table-responsive">
  <table class="table table-hover table-striped">
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
        <td>
          <strong style="color: green;">{{$journey->accept}}</strong> accepted <br />
          <strong style="color: red;">{{$journey->reject}}</strong> rejected <br />
          <strong style="color: black;">{{$journey->pending}}</strong> pending <br />
          <a href="/booking/offer_view/{{$journey->id}}">Find Out More</a></td>
      </tr>
      @endforeach
  </table>
</div>
<br />
<p>&nbsp;</p>

@endif
@endsection