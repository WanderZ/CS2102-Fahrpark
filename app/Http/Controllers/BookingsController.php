<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Journey;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\User;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class BookingsController extends Controller
{

  public function index()
  {
    return view('booking.index');
  }

  protected function doCreate(Request $request)
  {
    $journeyId = $request->input('journey_id');
    $userId = \Auth::user()->id;
    $status = 'PENDING';
    $remarks = $request->input('remarks');
    if(strlen(trim($remarks)) == 0){
      $remarks = 'No comment';
    }

    if (Booking::doCreate($journeyId, $userId, $status, $remarks)) {
      $request->session()->flash('success', 'Journey successfully booked');
    }
    else{
      $request->session()->flash('error', 'Journey is no longer available');
    }
    return Redirect::back();
  }

  protected function offer(){
    $user = \Auth::user();
    $userId = $user->id;

    $journeys = Booking::getStatusOfJourney($userId);

    return view('booking.offer', ['journeys'=>$journeys]);
  }

  protected function allOffer(){
    $journeys = Booking::getStatusOfAllJourney();

    return view('booking.offer', ['journeys'=>$journeys]);
  }

  protected function offerView($id){
    $user = \Auth::user();
    $userId = $user->id;

    $journey = Journey::getJourneyByKeys($id);
    if (! $journey || ! count($journey) ) {
      return $this->index();
    }
    $data['journey'] = $journey[0];

    $bookings = Booking::getOfferOfJourney($id);
    $data['bookings'] = $bookings;

    //Check if can still Accept Booking. Otherwise cannot approve anymore
    $data['canAccept'] = true;
    $accept = \DB::select("SELECT * FROM Bookings b WHERE b.status='ACCEPT' AND b.journey_id=".$id);
    $accept = count($accept);
    $numSeats = Vehicle::retrieveByPlateNo($journey[0]->vehicle)->numSeat;

    if ($numSeats - $accept <= 1) {
      $data['canAccept'] = false;
    }

    return view('booking.offer_view', $data);
  }

  protected function acceptOffer(Request $request, $journey_id, $booking_id){
    $data['canAccept'] = true;
    $accept = \DB::select("SELECT * FROM Bookings b WHERE b.status='ACCEPT' AND b.journey_id=".$journey_id);
    $accept = count($accept);
    $numSeats = \DB::select("SELECT v.numSeat FROM Vehicles v, Bookings b, Journeys j
        WHERE j.vehicle=v.plateNo
        AND b.journey_id=j.id
        AND j.id=".$journey_id);
    $numSeats = count($numSeats) > 0 ? $numSeats[0]->numSeat : 0;
    // Too many Accepted
    if ($numSeats - $accept <= 1) {
      $request->session()->flash("error", "Number of bookings exceeded number of seats in vehicle!");
      return $this->offerView($journey_id);

    } else {
      Booking::updateStatus($booking_id, 'ACCEPT');
      $approver = \Auth::user()->id;
      \App\Models\Transaction::createTransactionRecord($approver, $journey_id, $booking_id);
      return $this->offerView($journey_id);
    }
  }

  protected function rejectOffer($journey_id, $booking_id){
    Booking::updateStatus($booking_id, 'REJECT');
    return $this->offerView($journey_id);
  }

  protected function request(){
    $user = \Auth::user();
    $userId = $user->id;

    $journeys = Booking::getRequest($userId);

    return view('booking.request', ['journeys'=>$journeys]);
  }

  protected function edit($bookingId)
  {
    $booking = Booking::getBooking($bookingId);
    $data['booking'] = $booking[0];
    
    # For the dropdown selection for administration
    $userData = User::getUserNameIdPair();
    $data['usersDat'] = $userData;
    
    return view('booking.edit', $data);
  }

  protected function doEdit(Request $request)
  {
    $journeyId = $request->input('journey_id');
    $username = $request->input('username');
    $status = $request->input('status');
    $remarks = $request->input('remarks');

    $journey = Journey::getJourneyByKeys($journeyId);
    if(count($journey) == 0){
      $request->session()->flash('error', 'Invalid Journey Id');
      return Redirect::back();
    }

    try{
      $user = User::getUserId($username);
    }
    catch (\Exception $e){
      $request->session()->flash('error', 'Invalid User');
      return Redirect::back();
    }
    $userId = $user->id;

    $booking = Booking::editBooking($journeyId, $userId, $status, $remarks);

    $request->session()->flash('success', 'Update successful!');
    return Redirect::back();
  }

  protected function requestDelete($bookingId){
    if(Booking::deleteRequest($bookingId)){
      $request->session()->flash('success', 'You have deleted the request');
    }
    else{
      $request->session()->flash('success', 'The request is no longer available');
    }
    return Redirect::back();
  }
}
