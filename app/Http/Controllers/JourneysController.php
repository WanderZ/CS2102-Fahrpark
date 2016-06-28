<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Journey;
use App\Models\Vehicle;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class JourneysController extends Controller
{
  protected $redirectPath = '/journey/';

  public function __construct(Journey $model)
  {
    $this->model = $model;
  }

  /**
     * Display a listing of All Journeys.
     *
     * @return \Illuminate\Http\Response
     */
  protected function index()
  {
    $journeys = $this->model->getAll();

    return view('journey.index', ['journeys'=> $journeys]);
  }

  protected function myJourneys()
  {
    $userId = \Auth::user()->id;
    $journeys = $this->model->getAllUserJourney($userId);
    return view('journey.index', ['journeys'=> $journeys]);
  }

  protected function journeyAdvSearch()
  {
    return view('journey.search');
  }


  /**
     * Show the form for creating a new Journey.
     *
     * @return \Illuminate\Http\Response
     */
  protected function create()
  {
    $now = (date('Y-m-d\TH:i:s'));
    $data = ['now' => $now];
    $data['vehicles'] = array();

    $user = \Auth::user();

    if ($user && $user->isAdmin()) {
      $vehicles = Vehicle::retrieveByKeyword("", 1, 0);
      if ($vehicles) {
        $data['vehicles'] = $vehicles;
      }
    } else if ($user){
      $vehicles = Vehicle::retrieveByUserId($user->id);
      if ($vehicles) {
        $data['vehicles'] = $vehicles;
      }
    }

    $data['startMap'] = $this->getMaps("map_start","start");
    $data['endMap'] = $this->getMaps("map_end","end");
    return view('journey.create', $data);
  }

  /**
     * Shows a Journey.
     *
     * @return \Illuminate\Http\Response
     */
  protected function view($id)
  {
    $journey = $this->model->getJourneyByKeys($id);
    if (! $journey || ! count($journey) ) {
      return $this->index();
    }

    $data = array();
    $data['journey'] = $journey[0];
    $data['map'] = $this->getMaps("map", '', $data['journey']->start, $data['journey']->end);

    $data['isAdmin'] = false;
    $data['isOwner'] = false;
    $data['isBooked'] = false;

    $user = \Auth::user();
    if($user) {
      $userId = $user->id;


      $vehicle = \App\Models\Vehicle::retrieveByUserId($userId);

      if ($vehicle && count($vehicle) > 0){
        foreach($vehicle as $v){
          if ($v->plateNo == $journey[0]->vehicle) {
            $data['isOwner'] = true;
            break;
          }
        }
      }


      $data['BookingRemarks'] = '';

      $bookings = \App\Models\Booking::getRequest($userId);
      foreach($bookings as $booking){
        if ($booking->journey_id == $journey[0]->id) {
          $data['isBooked'] = true;
          $data['BookingRemarks'] = $booking->remarks;
          break;
        }
      }

      if($user->isAdmin()){
        $data['isAdmin'] = true;
      }
    }

    $data['driver'] = \App\Models\User::getUsername(\App\Models\Vehicle::retrieveByPlateNo($data['journey']->vehicle)->id)[0]->username;
    return view('journey.view', $data);
  }

  /*
	 * Search Journeys by fixed term
	 *
	 */

  protected function journeySimpleSearch(Request $Request){

    $term = $Request->all()['search'];
    $journeys = $this->model->getJourneysLikeTerm($term);

    return view('journey.index', ['journeys'=> $journeys]);
  }

  protected function getMaps($map_name="map", $input_ID='', $directionsStart="", $directionsEnd='', $marker=array()){
    //Google maps instationation
    $config['center'] = '1.3000, 103.8000';
    $config['zoom'] = 'auto';
    $config['places'] = TRUE;
    $config['map_name'] = $map_name;
    $config['map_div_id'] = 'map_canvas'.$map_name;

    if($directionsStart != "" && $directionsEnd != ""){
      $config['directions'] = TRUE;
    }
    $config['directionsStart']=$directionsStart;
    $config['directionsEnd']=$directionsEnd;

    $config['placesAutocompleteInputID'] = $input_ID;
    $config['placesAutocompleteBoundsMap'] = TRUE; // set results biased towards the maps viewport
    $config['placesAutocompleteOnChange'] =  '
        iw_'.$config['map_name'].'.close();
        var place'.$config['map_name'].' = placesAutocomplete'.$config['placesAutocompleteInputID'].'.getPlace();
        var marker'.$config['map_name'].' = new google.maps.Marker({
            map: '.$config['map_name'].',
            anchorPoint: new google.maps.Point(0, -29)
        });
        if (place'.$config['map_name'].'.geometry.viewport) {
            '.$config['map_name'].'.fitBounds(place'.$config['map_name'].'.geometry.viewport);
        } else {
            '.$config['map_name'].'.setCenter(place'.$config['map_name'].'.geometry.location);
            '.$config['map_name'].'.setZoom(17);  // Why 17? Because it looks good.
        }
          marker'.$config['map_name'].'.setIcon(/** @type {google.maps.Icon} */({
            url: place'.$config['map_name'].'.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
           }));
            marker'.$config['map_name'].'.setPosition(place'.$config['map_name'].'.geometry.location);
            marker'.$config['map_name'].'.setVisible(true);
            iw_'.$config['map_name'].'.open('.$config['map_name'].', marker'.$config['map_name'].');
            document.getElementById(\''.$config['placesAutocompleteInputID'].'Lat\').value=place'.$config['map_name'].'.geometry.location.lat();
            document.getElementById(\''.$config['placesAutocompleteInputID'].'Lng\').value=place'.$config['map_name'].'.geometry.location.lng();
        ';

    \Gmaps::initialize($config);

    if(count($marker) > 0){
      \Gmaps::add_marker($marker);
    }
    return \Gmaps::create_map();
  }

  /**
     * Show the form for editing a Journey.
     *
     * @return \Illuminate\Http\Response
     */
  protected function edit($id)
  {
    $journey = $this->model->getJourneyByKeys($id);
    if (! $journey || ! count($journey) ) {
      return $this->index();
    }
    $data = array();
    $data['journey'] = $journey[0];
    $smarker = array();
    $smarker['position'] = $data['journey']->startLat .', '.$data['journey']->startLng;
    $data['startMap'] = $this->getMaps("map_start","start",'','',$smarker);
    $emarker= array();
    $emarker['position'] = $data['journey']->endLat .', '.$data['journey']->endLng;
    $data['endMap'] = $this->getMaps("map_end","end",'','',$emarker);
    $data['vehicle'] = array();

    $user = \Auth::user();
    if ($user->isAdmin()) {
      $vehicles = Vehicle::retrieveAll();
      if ($vehicles) {
        $data['vehicles'] = $vehicles;
      }
    } else {
      $vehicles = Vehicle::retrieveByUserId($user->id);
      if ($vehicles) {
        $data['vehicles'] = $vehicles;
      }
    }

    return view('journey.edit', $data);
  }



  /**
     * Get a validator for an incoming creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'start' => 'required|max:255',
      'end' => 'required|max:255',
      'departureDatetime' => 'required|date',
      'arrivalDatetime' => 'required|date',
      'cost' => 'required|numeric',
      'remarks' => 'required|string',
      'startLat' => 'required|numeric',
      'startLng' => 'required|numeric',
      'endLat' => 'required|numeric',
      'endLng' => 'required|numeric',
    ]);
  }

  protected function postAdvSearch(Request $request) {

    $journeys = $this->model->getSearchedJourney($request->all());

    return view('journey.index', ['journeys'=> $journeys]);
  }

  /**
     * Creates a journey with given user's car
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  protected function postCreate(Request $request){

    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      $this->throwValidationException(
        $request, $validator
      );
    }

    $user = \Auth::user();
    if ($user) {
      $userId = $user->id;
      $data = $request->all();


      $vehicle = Vehicle::retrieveByUserId($userId);
      if ($vehicle && count($vehicle) && !$user->isAdmin) {

        $ownVehicle = false;
        foreach ($vehicle as $v) {
          if ($data['vehicle'] == $v->plateNo) {
            $ownVehicle = true;
            break;
          }
        }

        if (!$ownVehicle) {
          $request->session()->flash('error', 'Please Register a Vehicle');
          return redirect('/vehicle/create');
        }
      }

      if ($user->isAdmin || $ownVehicle) {
        try {
          $journey = $this->store($data);
          if ($journey) {
            $request->session()->flash('success', 'Journey created successfully!');
            return redirect($this->redirectPath);
          } else {
            $request->session()->flash('error', 'Invaild Input!');
            return Redirect::back();
          }
        } catch (\Illuminate\Database\QueryException $e) {
          $request->session()->flash('error', 'Cannot Arrive before Departing and Negative Cost !');
          return Redirect::back();
        }
      }
    } else {
      $request->session()->flash('error', 'Please Login In!');
      return redirect('/auth/login');
    }
  }

  /**
     * Create a new journey instance
     *
     * @param  array  $data
     * @return Journey
     */
  protected function store(array $data)
  {
    return Journey::doInsert([
      'start' => $data['start'],
      'end' => $data['end'],
      'departureDatetime' => $data['departureDatetime'],
      'arrivalDatetime' => $data['arrivalDatetime'],
      'cost' => $data['cost'],
      'remarks' => $data['remarks'],
      'startLat' => $data['startLat'],
      'startLng' => $data['startLng'],
      'endLat' => $data['endLat'],
      'endLng' => $data['endLng'],
      'vehicle' => $data['vehicle'],
    ]);

  }

  /**
     * Handle a request to Edit a journey instance
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  protected function postEdit(Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      $this->throwValidationException(
        $request, $validator
      );
    }

    $user = \Auth::user();
    if ($user) {
      $userId = $user->id;
      $vehicle = Vehicle::retrieveByUserId($userId);
      $isOwner = false;

      if ($vehicle && count($vehicle)) {
        foreach ($vehicle as $v) {
          if ($v->plateNo == $request->all()['vehicle']) {
            $isOwner = true;
            break;
          }
        }
      }


      if ($user->isAdmin ||
          $isOwner) {

        $data = $request->all();

        $attributes = [
          'id'=>$data['id'],
          'vehicle' => $data['vehicle'],
          'start' => $data['start'],
          'end' => $data['end'],
          'departureDatetime' => $data['departureDatetime'],
          'arrivalDatetime' => $data['arrivalDatetime'],
          'cost' => $data['cost'],
          'remarks' => $data['remarks'],
          'startLat' => $data['startLat'],
          'startLng' => $data['startLng'],
          'endLat' => $data['endLat'],
          'endLng' => $data['endLng'],
        ];
        $whereQuery = [
          'id' => $data['id'],
        ];
        try {
          $result = $this->model->doUpdate($whereQuery, $attributes);
          if ($result) {
            $request->session()->flash('success', 'Journey edited successfully!');
            return redirect($this->redirectPath);
          } else {
            $request->session()->flash('error', 'No Changes Detected!');
            return Redirect::back();
          }
        } catch (\Illuminate\Database\QueryException $e) {
          $request->session()->flash('error', 'Invaild Input!');
          return Redirect::back();
        }
      } else {
        //Wrong Car also Cannot
        $request->session()->flash('error', 'Not a Valid Vehicle Selection!');
        return Redirect::back();
      }
    } else {
      //Wrong User cannot Edit
      $request->session()->flash('error', 'Please Login to Edit!');
      return redirect('/auth/login');
    }
  }

  /**
     * Handle a request to Delete a journey instance
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  protected function postDelete(Request $request)
  {
    $user = \Auth::user();
    if ($user) {
      $isOwner = false;
      $userId = $user->id;
      $vehicle = Vehicle::retrieveByUserId($userId);
      if ($vehicle && count($vehicle)) {
        foreach ($vehicle as $v) {
          if ($request->exists('vehicle') &&
              $v->plateNo == $request->all()['vehicle']) {
            $isOwner = true;
            break;
          }
        }
      }
      $isAdmin = $user->isAdmin();

      if($isOwner || $isAdmin) {

        $data = $request->all();

        $attributes =  [
          'id'=>$data['id'],
        ];
        $results = false;

        if ($isAdmin) {
          $results = \App\Models\Journey::doDelete($attributes);
        } else if ($isOwner) {
          $results = \App\Models\Journey::softDelete($data['id']);
        }

        if ($results) {
          $request->session()->flash('success', 'Journey deleted successfully!');
          return redirect('/journey');
        }else {
          $request->session()->flash('error', 'Journey deleted unsuccessfully!');
          return Redirect::back();
        }
      } else {
        //Wrong Car also Cannot
        $request->session()->flash('error', 'Unable to delete other\'s journey!');
        return Redirect::back();
      }
    } else {
      //Wrong User cannot Delete
      $request->session()->flash('error', 'Please Login to Delete!');
      return redirect('/auth/login');
    }
  }

}
