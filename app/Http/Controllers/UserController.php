<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index(Request $request)
  {
    $keywords = $request->input('keywords');
    $limit = 10;
    $page = $request->input('page') ? $request->input('page') : 1;

    if ($keywords) {
      list($users, $moreResultsCount) = User::searchUsersPaginated($keywords, $page - 1, $limit);
      return view('user.index', ['title' => 'Searching for "' . $keywords . '"', 'keywords' => $keywords, 'users' => $users, 'page' => $page, 'limit' => $limit, 'totalResults' => $moreResultsCount]);
    } else {
      list($users, $moreResultsCount) = User::getAllUsersPaginated($page - 1, $limit);
      return view('user.index', ['users' => $users, 'page' => $page, 'limit' => $limit, 'totalResults' => $moreResultsCount]);
    }
  }

  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function create(Request $request)
  {
    if ($request->isMethod('post')) {
      $authController = new \App\Http\Controllers\Auth\AuthController;
      $validator = $authController->validator($request->all());

      if ($validator->fails()) {
        return redirect($request->url())->withErrors($validator);
      }

      if ($authController->create($request->all())) {
        $request->session()->flash('success', 'User created successfully!');
        return redirect('users');
      } else {
        $request->session()->flash('error', 'Failed to create user. An unknown error has occurred.');
        return view('user.create');
      }
    } else {
      return view('user.create');
    }
  }

  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
  {
    //
  }

  /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function show($username)
  {
    $user = User::where('username', $username)->firstOrFail();
    return view('user.view', ['user' => $user]);
  }

  public function changePassword(Request $request)
  {
    $user = \Auth::user();
    if (!\Hash::check($request->input('password'), $user->password)) {
      $request->session()->flash('error', 'Wrong password provided!');
      return redirect('user/' . $user->username);
    }

    $validator = \Validator::make($request->all(), [
      'new_password' => 'required|confirmed|min:6',
    ]);

    if ($validator->fails()) {
      return redirect('user/' . $user->username)->withErrors($validator);
    }

    if (\Hash::check($request->input('new_password'), $user->password)) {
      $request->session()->flash('error', 'New password cannot be the same as old password!');
      return redirect('user/' . $user->username);
    }

    if ($user->changePassword($request->input('new_password'))) {
      $request->session()->flash('success', 'Password changed succesfully!');
    } else {
      $request->session()->flash('error', 'Failed to change password. An unknown error has occurred.');
    }
    return redirect('user/' . $user->username);
  }

  public function updateProfile(Request $request, $username)
  {
    $user = \Auth::user();
    if ($user->username != $username) {
      if (!$user->isAdmin()) {
        $request->session()->flash('error', 'You can only update your own profile!');
        return redirect('user/' . $username);
      }
    }

    $validator = \Validator::make($request->all(), [
      'fullname' => 'required|max:255',
      'phone' => 'required|max:45',
    ]);

    if ($validator->fails()) {
      return redirect('user/' . $username)->withErrors($validator);
    }

    $values = ['fullname' => $request->input('fullname'), 'phone' => $request->input('phone')];
    if ($user->isAdmin()) {
      $values['isAdmin'] = $request->input('isAdmin') == 'on' ? 1 : 0;
    }

    if ($user->doUpdate(['username' => $username], $values)) {
      $request->session()->flash('success', 'Profile updated succesfully!');
    } else {
      $request->session()->flash('error', 'Failed to update profile. An unknown error has occurred.');
    }
    return redirect('user/' . $username);
  }

  public function deleteUser(Request $request, $username)
  {
    if (User::deleteUser($username)) {
      $request->session()->flash('success', 'User deleted succesfully!');
    } else {
      $request->session()->flash('error', 'Failed to delete user. An unknown error has occurred.');
    }

    return redirect('users');
  }

  /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit($id)
  {
    //
  }

  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function update(Request $request, $id)
  {
    //
  }

  /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function destroy($id)
  {
    //
  }
}
