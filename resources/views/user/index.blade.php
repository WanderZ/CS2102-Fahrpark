@extends('layout')

@section('page-title')
{{ isset($title) ? $title : 'Viewing All Users' }}
@endsection

@section('content')
<h1>{{ isset($title) ? $title : 'Viewing All Users' }}</h1><br/>
<form class="form-inline" role="form" method="GET">
  <div class="input-group">
    <input class="form-control" type="text" name="keywords" placeholder="Search for users..." value="{{ isset($keywords) ? $keywords : '' }}" />
    <span class="input-group-btn">
      <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </span>
  </div>
  <div class="hidden-xs pull-right"><a class="btn btn-primary" href="{{ url('users/create') }}">Create New User</a></div><br />
  <div class="visible-xs-block"><a class="btn btn-block btn-primary" href="{{ url('users/create') }}">Create New User</a></div>
  <p style="clear: both;">&nbsp;</p>
</form>
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <tr>
      <th>Id</th>
      <th>Username</th>
      <th>Fullname</th>
      <th>Phone</th>
      <th>Is Admin</th>
      <th>Last Login</th>
      <th>Registered On</th>
    </tr>
    @foreach ($users as $user)
    <tr>
      <td>{{ $user->id }}</td>
      <td><a href="{{ url('user/' . $user->username) }}" style="text-transform: capitalize;">{{ $user->username }}</a></td>
      <td>{{ $user->fullname }}</td>
      <td>{{ $user->phone }}</td>
      <td>{{ $user->isAdmin ? 'Yes' : 'No' }}</td>
      <td><?php echo ($user->lastLogin=="0000-00-00 00:00:00") ? "Never" : $user->lastLogin; ?></td>
      <td>{{ $user->createdAt }}</td>
    </tr>
    @endforeach
  </table>
  <div style="text-align: right;">
    Showing {{ count($users) }} of {{ $totalResults }} total users
    @for ($i = 0; $i <= $totalResults/$limit; $i++)
                                            <a href="{{ url('users') }}?page={{ $i+1 }}" class="btn btn-default" @if ($page == $i+1) style="font-weight: bold;" @endif>{{ $i+1 }}</a>
  @endfor
</div>
</div>
@endsection