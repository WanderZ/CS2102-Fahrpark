@extends('layout')

@section('page-title')
    Edit Vehicle
@endsection

@section('content')
    <h1>Vehicle Edit: {{ $vehicle->plateNo }}</h1>
    @if ((\Auth::user()->isAdmin == 0) && (\Auth::user()->id != $vehicle->driver))
        <h3>You are not authorized to view this page</h3>
    @else
    <form class="form-horizontal" role="form" method="POST" action="/vehicle/{{ $vehicle->plateNo }}">
        {!! csrf_field() !!}
        <input name="_method" type="hidden" value="PUT">

        <div class="form-group">
          <label class="col-sm-2 control-label" for="plateNo">Plate number:</label>
          <div class="col-sm-10">
            <input class="form-control" id="plateNo" type="text" name="plateNo" value="{{ $vehicle->plateNo }}" readonly="readonly">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="numSeat">Number of seats:</label>
          <div class="col-sm-10">
            <input class="form-control" id="numSeat" type="number" min="0" max="99" step="1" name="numSeat" value="{{ $vehicle->numSeat }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="brand">Brand:</label>
          <div class="col-sm-10">
            <input class="form-control" id="brand" type="text" name="brand" value="{{ $vehicle->brand }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="model">Model:</label>
          <div class="col-sm-10">
            <input class="form-control" id="model" type="text" name="model" value="{{ $vehicle->model }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="color">Color:</label>
          <div class="col-sm-10">
            <input class="form-control" id="color" type="text" name="color" value="{{ $vehicle->color }}">
          </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="color">Owner:</label>
            <div class="col-sm-10">
                <input class="form-control" id="owner" type="text" name="owner" value="{{ $vehicle->username }}" readonly="readonly">
            </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-primary" type="submit">Update</button>
            <a class="btn btn-default" href="/vehicle/{{ $vehicle->plateNo }}">Cancel</a>
          </div>
        </div>
    </form>
@endif
@endsection