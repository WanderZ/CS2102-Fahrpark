@extends('layout')

@section('page-title')
Create
@endsection

@section('content')
<h1>Vehicle Create</h1>
<?php # echo '<pre>';var_dump($rsUsers); echo '</pre>'; ?>
<form class="form-horizontal" role="form" method="POST" action="/vehicle">
  {!! csrf_field() !!}

  <div class="form-group">
    <label class="col-sm-2 control-label" for="plateNo"> Plate number:</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="plateNo" name="plateNo">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="numSeat"> Number of seats:</label>
    <div class="col-sm-10">
      <input class="form-control" type="number" min="0" max="99" step="1" id="numSeat" name="numSeat">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="brand"> Brand:</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="brand" name="brand">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="model"> Model:</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="model" name="model">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label" for="color"> Color:</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" id="color" name="color">
    </div>
  </div>

  @if (\Auth::user()->isAdmin())
  <div class="form-group">
    <label class="col-sm-2 control-label" for="owner"> Owner:</label>
    <div class="col-sm-10">
      <select class="form-control" type="text" id="owner" name="owner">
        @foreach ($rsUsers as $user)
        <option value="{{ $user->id}}">{{ $user->username }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <?php /*
    @else
        <div class="form-group">
            <label class="col-sm-2 control-label" for="owner"> Owner:</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" id="owner" name="owner" value="{{ \Auth::user()->username }}" readonly="readonly">
            </div>
        </div>
  */ ?>
  @endif

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button class="btn btn-primary" type="submit">Create Vehicle</button>
    </div>
  </div>
</form>
@endsection