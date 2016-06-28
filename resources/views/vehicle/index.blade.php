@extends('layout')

@section('page-title')
Create
@endsection

@section('content')
@if (\Auth::user()->isAdmin())
<h1>List of all vehicles</h1><br/>
@else
<h1>My vehicles</h1><br/>
@endif
<form class="form-inline" role="form" action="/vehicle/search" method="GET">
  <div class="form-group">
    <div class="input-group">
      <input class="form-control" type="text" id="search" name="search" placeholder="Enter your query here">
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
      </span>
    </div>
  </div>
  <a href="\vehicle\create" role="button" class="hidden-xs btn btn-primary pull-right">Create New Vehicle</a>
  <a href="\vehicle\create" role="button" class="hidden-sm hidden-md hidden-lg btn btn-primary btn-block">Create New Vehicle</a>
</form><br/>
<p style="border-bottom: thin dotted;">&nbsp;</p>
@if (count($vehicle) == 0)
<h3>There are no vehicles</h3>
@else
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Plate number</th>
        <th>Number of seats</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Color</th>
        @if (\Auth::user()->isAdmin())
        <th>Owner</th>
        @endif
      </tr>
    </thead>
    @foreach( $vehicle as $v )
    @if ($v->deletedAt != NULL)
    <tr class="bg-danger">
      @else
    <tr>
      @endif
      <td><a href="{{ route('vehicle.show', $v->plateNo) }}">{{ $v->plateNo }}</a></td>
      <td>{{ $v->numSeat }}</td>
      <td>{{ $v->brand }}</td>
      <td>{{ $v->model }}</td>
      <td>{{ $v->color }}</td>
      @if (\Auth::user()->isAdmin())
      <td><a href="{{ url('user/' . $v->username) }}" style="text-transform: capitalize;">{{ $v->username }}</a></td>
      @endif
    </tr>
    @endforeach
  </table>
</div>
@endif

@endsection