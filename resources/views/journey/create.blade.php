@extends('layout')

@section('page-title')
  Create Your Journey
@endsection

@push('head')
  {!!$startMap['js']!!}
  {!!$endMap['js']!!}
@endpush

@section('content')
<h1>Create New Journey</h1><br />
<form class="form-horizontal" role="form" id="create" method="POST" action="/journey/create">
  {!! csrf_field() !!}
  <input type="hidden" id="startLat" name="startLat" />
  <input type="hidden" id="startLng" name="startLng" />
  <input type="hidden" id="endLat" name="endLat" />
  <input type="hidden" id="endLng" name="endLng" />

  <div class="form-group">
    <label class="col-sm-2 control-label" for="vehicle">Vehicle</label>
    <div class="col-sm-10">
      <select class="form-control" id="vehicle" name="vehicle" form="create">
        @foreach($vehicles as $vehicle)
          <option value="{{$vehicle->plateNo}}"> {{$vehicle->plateNo}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="start">Starting Location</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="start" name="start">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="end">Ending Location</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="end" name="end">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="departureDatetime">Starting Time</label>
    <div class="col-sm-10">
      <input class="form-control" type='datetime-local' name="departureDatetime" value="{{$now}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="arrivalDatetime">Ending Time</label>
    <div class="col-sm-10">
      <input class="form-control" type="datetime-local" name="arrivalDatetime" value="{{$now}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="cost">Cost</label>
    <div class="col-sm-10">
      <div class="input-group">
        <span class="input-group-addon" id="cost">&#36;</span>
        <input class="form-control" type="number" min="0" max="9999" step="0.01" name="cost">
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="remarks">Remarks</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="remarks">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary" type="submit">Create Journey</button></div>
  </div>
</form>
<p>&nbsp;</p>
<div class="col-md-12">
  <div class="col-md-6">
     <label class="col-sm-12 control-label" for="remarks">Starting Location</label>
    {!!$startMap['html']!!}
  </div>
  <div class="col-md-6">
     <label class="col-sm-12 control-label" for="remarks">Ending Location</label>
    {!!$endMap['html']!!}
  </div>
</div>
@endsection