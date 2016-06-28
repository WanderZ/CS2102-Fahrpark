@extends('layout')

@section('page-title')
  Edit Your Journey
@endsection

@push('head')
  {!!$startMap['js']!!}
  {!!$endMap['js']!!}
@endpush

@section('content')
<h1>Edit Journey</h1><br />
<form class="form-horizontal" id="edit" role="form" method="POST" action="/journey/edit/{{$journey->id}}">
  {!! csrf_field() !!}
  <input type="hidden" id="startLat" name="startLat" value="{{$journey->startLat}}">
  <input type="hidden" id="startLng" name="startLng" value="{{$journey->startLng}}">
  <input type="hidden" id="endLat" name="endLat" value="{{$journey->endLat}}">
  <input type="hidden" id="endLng" name="endLng" value="{{$journey->endLng}}">
  <input type="hidden" name="id" readonly='readonly'  value="{{$journey->id}}" >

  <div class="form-group">
    <label class="col-sm-2 control-label" for="vehicle">Vehicle</label>
    <div class="col-sm-10">
      <select class="form-control" id="vehicle" name="vehicle" form="edit" selected="{{$journey->vehicle}}">
        @foreach($vehicles as $vehicle)
          <option value="{{$vehicle->plateNo}}"> {{$vehicle->plateNo}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="start">Starting Location</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="start" name="start" value="{{$journey->start}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="end">Ending Location</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="end" name="end" value="{{$journey->end}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="departureDatetime">Starting Time</label>
    <div class="col-sm-10">
      <input class="form-control" type='datetime-local' name="departureDatetime" value="{{strftime('%Y-%m-%dT%H:%M:%S', strtotime($journey->departureDatetime))}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="arrivalDatetime">Ending Time</label>
    <div class="col-sm-10">
      <input class="form-control" type="datetime-local" name="arrivalDatetime" value="{{strftime('%Y-%m-%dT%H:%M:%S', strtotime($journey->arrivalDatetime))}}">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="cost">Cost</label>
    <div class="col-sm-10">
      <div class="input-group">
        <span class="input-group-addon" id="cost">&#36;</span>
        <input class="form-control" type="number" min="0" max="9999" step="0.01"  name="cost" value="{{$journey->cost}}">
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="remarks">Remarks</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="remarks" value="{{$journey->remarks}}">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-primary" type="submit">Update Journey</button></div>
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