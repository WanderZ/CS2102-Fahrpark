@extends('layout')

@section('page-title')
View Journey Details
@endsection

@push('head')
{!!$map['js']!!}
@endpush

@section('content')
<h1> Journey Details :: {{$driver}}'s {{$journey->vehicle}}</h1><br/>
<div style="display: inline-block; width: 50%; min-width: 300px; vertical-align: text-top;">
  <dl class="dl-horizontal">
    <dt>Created At:</dt>
    <dd>{{$journey->createdAt}}</dd>
    <dt>Pickup:</dt>
    <dd>{{$journey->start}}</dd>
    <dt>Dropoff:</dt>
    <dd>{{$journey->end}}</dd>
    <dt>Pickup Timing:</dt>
    <dd>{{$journey->departureDatetime}}</dd>
    <dt>Estimated Arrival:</dt>
    <dd>{{$journey->arrivalDatetime}}</dd>
    <dt>Cost:</dt>
    <dd>SGD&#36;{{$journey->cost}}</dd>
    <dt>Driver's Remarks:</dt>
    <dd>{{$journey->remarks}}</dd>
  @if ($isOwner || $isAdmin)
    <dt>&nbsp;</dt>
    <dd>&nbsp;</dd>
    <dt>&nbsp;</dt>
    <dd>
        <form class="form-inline" role="form" method="POST" action="/journey/delete">
          {!! csrf_field() !!}
          <input name="vehicle" type="hidden" value="{{$journey->vehicle}}">
          <input name="id" type="hidden" value="{{$journey->id}}">
          <a href="/journey/edit/{{$journey->id}}" role="button" class="btn btn-info">Update </a>
          @if ($isOwner)
          <button class="btn btn-danger" type="submit">Delete</button>
          @elseif ($isAdmin)
          <button class="btn btn-danger" type="submit" onclick="if(!confirm('Are you sure delete this record?')){return false;}">Delete</button>
          @endif
        </form>
    </dd>
  @endif
  </dl>
  {{--start of booking--}}
  @if(!$isOwner && !$isAdmin)
  <div style="display: table-row;">
    <dl class="dl-horizontal">
    @if ($isBooked)
      <dt>Booking Remarks:</dt>
      <dd>{{$BookingRemarks}}</dd>
    @elseif (!$isAdmin)
      <dt>&nbsp;</dt>
      <dd>
        <form class="form-inline" role="form" method="POST" action="/journey/view/{{$journey->id}}">
          <div class="form-group">
            <input class="form-control" type="text" name="remarks" placeholder="Leave a request if any" />
            <input class="form-control" type="hidden" name="journey_id" value="{{$journey->id}}" />
            <input class="form-control" type="hidden" name="_token" value="{{{ csrf_token() }}}" />
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit"><i class="fa fa-car"></i> Book </button>
          </div>
        </form>
      </dd>
    @endif
    </dl>
  </div>
  @endif
  {{--end of booking--}}
</div>
<div style="display: inline-block; width: 47%; min-width: 300px; vertical-align: text-top;">
  {!!$map['html']!!}
</div>
@endsection