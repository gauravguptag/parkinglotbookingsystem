<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\BookingHelper;
use App\Model\BookingLog;
use App\Model\ParkingLotConstant;
use Response,Json,Exception;

use App\Http\Requests\AdvanceBookingSlotRequest;
use App\Http\Requests\BookingSlotRequest;

class BookingController extends Controller
{
    public function getAllAvailableParkingSlot(Request $request){
        try{
            $data = BookingHelper::fetchAllAvailableParkingSlot();

            return Response::json(['status'=> 'success','msg'=>'All Available Parking Slot','data'=>$data]);
        } catch (Exception  $ex) {
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

    public function getAllOccupiedParkingSlot(Request $request){
        try{
            $data = BookingHelper::fetchAllOccupiedParkingSlot();

            return Response::json(['status'=> 'success','msg'=>'All Occupied Parking Slot','data'=>$data]);
        } catch (Exception  $ex) {
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

    public function getAllRegisteredUser(Request $request){
        try{
            $data = BookingHelper::fetchAllRegisteredUser();

            return Response::json(['status'=> 'success','msg'=>'All Registered User','data'=>$data]);
        } catch (Exception  $ex) {
            dd($ex);
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

    public function advanceBookingSlot(AdvanceBookingSlotRequest $request){
        try{
            $checkSlot = BookingHelper::fetchAllAvailableParkingSlot();
            if($checkSlot->count() <= 0){
                return Response::Json(['status'=>'failed','msg'=>'All Slots are Occupied'],200); 
            }

            $request['type'] = 'general';
            if($request['driver_type'] == 'pregnant' || $request['driver_type'] == 'differently-abled'){
                $request['type'] = 'reserved';
            }

            $parkingNumber = BookingHelper::advanceBooking($request);
            return Response::json(['status'=> 'success','msg'=>'slot booked successfully','data'=>['parkingNumber' => $parkingNumber]]);
        } catch (Exception  $ex) {
            dd($ex);
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

    public function bookingSlot(BookingSlotRequest $request){
        try{
            if(empty($request['parking_number'])){
                $checkSlot = BookingHelper::fetchAllAvailableParkingSlot();
                if($checkSlot->count() <= 0){
                    return Response::Json(['status'=>'failed','msg'=>'All Slots are Occupied'],200); 
                }
            }

            $request['type'] = 'general';
            if($request['driver_type'] == 'pregnant' || $request['driver_type'] == 'differently-abled'){
                $request['type'] = 'reserved';
            }

            $parkingNumber = BookingHelper::bookingSlot($request);
            return Response::json(['status'=> 'success','msg'=>'slot booked successfully','data'=>['parkingNumber' => $parkingNumber]]);
        } catch (Exception  $ex) {
            dd($ex);
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

    public function updateAllLateBooking(Request $request){
        try{
            dd('hello');
        } catch (Exception  $ex) {
            dd($ex);
                return Response::Json(['status'=>'failed','msg'=>$ex],200);
        }
    }

}
