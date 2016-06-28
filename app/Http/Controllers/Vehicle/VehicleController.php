<?php

namespace App\Http\Controllers\Vehicle;

use DB;
use Validator;
use App\Models\Vehicle;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth;

class VehicleController extends Controller
{
  /**
     * Display a listing of the Vehicles.
     *
     * @return \Illuminate\Http\Response
     */
  public function index()
  {
    if (\Auth::user()->isAdmin()) {
      $vehicle = Vehicle::retrieveAll();
    } else {
      $userId = \Auth::user()->id;
      $vehicle = Vehicle::retrieveByUserId($userId);
    }
    return view('vehicle.index', compact('vehicle'));
  }

  /**
     * Show the form for creating a new Vehicle.
     *
     * @return \Illuminate\Http\Response
     */
  public function create()
  {
    $rsUsers = User::getUserNameIdPair();
    return view('vehicle.create', ['rsUsers' => $rsUsers]);
  }

  /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      //'plateNo' => 'required|max:8|unique' . Vehicle::$tableName,
      'plateNo' => 'required|max:8',
      'numSeat' => 'required|integer',
      'brand' => 'required||max:255',
      'model' => 'required|max:255',
      'color' => 'required|max:255',
    ]);
  }

  /**
     * Store a newly created Vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
  {

    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      $this->throwValidationException(
        $request, $validator
      );
    }

    $data = $request->all();

    if (\Auth::user()->isAdmin()) {
      Vehicle::doInsert([
        'plateNo' => $data['plateNo'],
        'numSeat' => $data['numSeat'],
        'brand' => $data['brand'],
        'model' => $data['model'],
        'color' => $data['color'],
        'driver' => $data['owner'],
        'createdAt' => date("Y-m-d H:i:s"),
      ]);

      $vehicle = Vehicle::retrieveAll();
    } else {
      Vehicle::doInsert([
        'plateNo' => $data['plateNo'],
        'numSeat' => $data['numSeat'],
        'brand' => $data['brand'],
        'model' => $data['model'],
        'color' => $data['color'],
        'driver' => \Auth::user()->id,
        'createdAt' => date("Y-m-d H:i:s"),
      ]);

      $userId = \Auth::user()->id;
      $vehicle = Vehicle::retrieveByUserId($userId);
    }
    return view('vehicle.index', compact('vehicle'));
  }

  /**
     * Display the specified Vehicle.
     *
     * @param  string  $plateNo
     * @return \Illuminate\Http\Response
     */
  public function show($plateNo)
  {
    $vehicle = Vehicle::retrieveByPlateNo($plateNo);
    return view('vehicle.show', compact('vehicle'));
  }

  /**
     * Show the form for editing the specified Vehicle.
     *
     * @param  string  $plateNo
     * @return \Illuminate\Http\Response
     */
  public function edit($plateNo)
  {
    $vehicle = Vehicle::retrieveByPlateNo($plateNo);
    return view('vehicle.edit', compact('vehicle'));
  }

  /**
     * Update the specified Vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  //public function update(Request $request, $plateNo)
  public function update(Request $request)
  {
    $data = $request->all();
    $validator = $this->validator($data);

    if ($validator->fails()) {
      $this->throwValidationException(
        $request, $validator
      );
    }

    $query = 'UPDATE `Vehicles` SET `numSeat` = ?, `brand` = ?, `model` = ?, `color` = ? WHERE `plateNo` = ?';

    DB::statement($query, array($data['numSeat'], $data['brand'], $data['model'], $data['color'], $data['plateNo']));

    $vehicle = Vehicle::retrieveByPlateNo($data['plateNo']);
    return view('vehicle.show', compact('vehicle'));
  }

  /**
     * Remove the specified Vehicle from storage.
     *
     * @param  string  $plateNo
     * @return \Illuminate\Http\Response
     */
  public function destroy($plateNo)
  {
    if (\Auth::user()->isAdmin()) {
      $query = 'DELETE FROM `Vehicles` WHERE `plateNo` = ?';
      DB::statement($query, array($plateNo));

      $vehicle = Vehicle::retrieveAll();
    } else {
      $query = 'UPDATE `Vehicles` SET `deletedAt` = ? WHERE `plateNo` = ?';
      DB::statement($query, array(date("Y-m-d H:i:s"), $plateNo));

      $userId = \Auth::user()->id;
      $vehicle = Vehicle::retrieveByUserId($userId);
    }
    return view('vehicle.index', compact('vehicle'));
  }

  /**
     * Display a listing of the Vehicles with the specified term.
     *
     * @return \Illuminate\Http\Response
     */
  protected function search()
  {
    $keyword = $_GET['search'];
    $isAdmin = \Auth::user()->isAdmin();
    $userId = \Auth::user()->id;
    $vehicle = Vehicle::retrieveByKeyword($keyword, $isAdmin, $userId);

    return view('vehicle.index', compact('vehicle'));
  }
}
