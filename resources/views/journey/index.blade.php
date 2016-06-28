@extends('layout')

@section('page-title')
View Journeys
@endsection

@section('content')
<h1>Journeys</h1><br/>
<form class="form-inline" role="form" method="POST" action="/journey/search">
  <div class="form-group">
    {!! csrf_field() !!}
    <div class="input-group">
      <input class="form-control" type="text" id="search" name="search" placeholder="Enter your query here">
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
      </span>
    </div>
  </div>
  <a role="button" class="btn btn-primary pull-right hidden-xs" href="/journey/create">Create New Journey</a>
  <br/>
  <small class="hidden-xs"><a href="/journey/search">Advanced Search</a></small>
  <p style="clear: both;">
    <a role="button" class="hidden-lg hidden-md hidden-sm btn btn-primary btn-block" href="/journey/create">Create New Journey</a>
    <a role="button" class="hidden-lg hidden-md hidden-sm btn btn-block btn-primary" href="/journey/search">Advanced Search</a>
  </p>
</form><br/>
<p style="border-bottom: thin dotted;">&nbsp;</p>
<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
      <tr>
        <?php # <th>Vehicle</th> ?>
        <th>Route</th>
        <th>Pickup &amp; Dropoff</th>
        <th>Cost</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      @foreach($journeys as $journey)
      <tr>
        <?php #<td>{{$journey->vehicle}}</td>?>
        <td>
          <strong style="width: 25px;">Start</strong>: {{$journey->start}}
          <br /> to <br />
          <strong style="width: 25px;">End</strong>: {{$journey->end}}
        </td>
        <td>
          {{strftime('%Y-%m-%d %H:%M', strtotime($journey->departureDatetime))}}
          <br />to<br />
          {{strftime('%Y-%m-%d %H:%M', strtotime($journey->arrivalDatetime))}}
        </td>
        <td>&#36;{{ number_format($journey->cost, 2, '.', ',')}}</td>
        <td><a href="/journey/view/{{$journey->id}}">More Details</a></td>
      </tr>
      @endforeach
  </table>
</div>
@endsection
