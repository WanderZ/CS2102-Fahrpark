@extends('layout')

@section('page-title')
    Show vehicle
@endsection

@section('content')
    <h1>Show vehicle: {{ $vehicle->plateNo }}</h1>
    @if ((\Auth::user()->isAdmin == 0) && (\Auth::user()->id != $vehicle->driver))
        <h3>You are not authorized to view this page</h3>
    @else
    <dl class="dl-horizontal">
      <dt>Number of Seats:</dt>
      <dd>{{ $vehicle->numSeat }}</dd>
      <dt>Brand:</dt>
      <dd>{{ $vehicle->brand }}</dd>
      <dt>Model:</dt>
      <dd>{{ $vehicle->model }}</dd>
      <dt>Color:</dt>
      <dd>{{ $vehicle->color }}</dd>
      <dt>Owner:</dt>
      <dd>{{ $vehicle->username }}</dd>
      <dt>&nbsp;</dt>
      <dd>&nbsp;</dd>
      <dt>&nbsp;</dt>
      <dd>
        <form class="form-inline" method="POST" action="/vehicle/{{ $vehicle->plateNo }}">
          {!! csrf_field() !!}
          <input name="_method" type="hidden" value="DELETE">
          <a role="button" class="btn btn-info" href="/vehicle/{{ $vehicle->plateNo }}/edit">
            Update
          </a>
          <button class="btn btn-danger" type="submit">Delete</button>
      </form>
      </dd>
    </dl>
    @endif
@endsection
