@extends('layout')

@section('page-title')
Fahrpark
@endsection

@push('head')
<style type="text/css">
  .journey-snippet {
    display: inline-block;
    background: #fff;
    border-radius: 7px;
    border: #555 thick double;
    padding: 15px;
    margin: 15px;
  }
  .journey-snippet:hover {
    background: #def;
    text-decoration: none;
  }
  .journey-title {
    display: block;
    color: black;
    font-weight: 800;
    font-size: larger;
    margin-bottom: 5px;
  }
  .journey-details {
    display: block;
    color: #555;
    margin-bottom: 5px;
  }
</style>
@endpush

@section('content')
<h1>Fahrpark</h1>
<div class="container-fluid">
  <div class="row">
    @foreach ($routes as $route)
    <a class="journey-snippet" href="/journey/view/{{$route->id}}">
      <span class="journey-title">
        {{ $route->brand }} {{ $route->model }}
      </span>
      <span class="journey-details">
        <span style="font-weight: 700;">Pickup: </span>{{ $route->start }}
      </span>
      <span class="journey-details">
        <span style="font-weight: 700;">Dropoff: </span>{{ $route->end }}
      </span>
    </a>
    @endforeach
  </div>
</div>
@endsection