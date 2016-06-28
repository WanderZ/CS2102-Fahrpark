@extends('layout')

@section('page-title')
    View Your Journey
@endsection

@section('content')

    <form class="form-horizontal" role="form" method="POST" action="/journey/advsearch" id="advSearch">
        {!! csrf_field() !!}

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Vehicle No:</label>
            <div class="col-sm-10 col-md-10"><input class="form-control" type="text" name="vehicle" value=""></div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Starting Location</label>
            <div class="col-sm-10 col-md-10"><input class="form-control" type="text" id='start' name="start" value=""></div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Starting Time</label>
            <div class="col-sm-10 col-md-10"> 
                <select class="form-control" name="departureDatetimeOption" form="advSearch"> 
                    <option value="lessthan">Before</option>
                    <option value="morethan">After</option>
                    <option value="between">Between</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 col-md-12"> 
                <div class="col-sm-6 col-md-6"><input class="form-control" type='datetime-local' name="departureDatetime" value=""></div>
                <div class="col-sm-6 col-md-6"><input class="form-control" type='datetime-local' name="departureDatetime2" value=""></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Ending Location</label>
            <div class="col-sm-10 col-md-10"> 
                <input class="form-control" type="text" name="end" id='end' value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Ending Time</label>
            <div class="col-sm-10 col-md-10"> 
	            <select class="form-control" name="arrivalDatetimeOption" form="advSearch"> 
	                <option value="lessthan">Before</option>
	                <option value="morethan">After</option>
	                <option value="between">Between</option>
	            </select>
	        </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 col-md-12"> 
                <div class="col-sm-6 col-md-6"><input class="form-control" type="datetime-local" name="arrivalDatetime" value=""></div>
                <div class="col-sm-6 col-md-6"><input class="form-control" type='datetime-local' name="arrivalDatetime2" value=""></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Cost</label>
            <div class="col-sm-10 col-md-10">
	            <select class="form-control" name="costOption" form="advSearch"> 
	                <option value="lessthan">Less Than</option>
	                <option value="morethan">More Than</option>
	                <option value="between">Between</option>
	            </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 col-md-12"> 
                <div class="col-sm-6 col-md-6"><input class="form-control" type="number" name="cost" value=""></div>
                <div class="col-sm-6 col-md-6"><input class="form-control" type="number" name="cost2" value=""></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-md-2" for="">Remarks</label>
            <div class="col-sm-10 col-md-10">
                <input class="form-control" type="text" name="remarks" value="">
            </div>
        </div>

        <div>
            <button class="btn btn-block btn-primary" type="submit">Search</button>
        </div>
    </form>

@endsection
