<?php

namespace App\Helpers;


use App\Model\BookingLog;
use App\Model\ParkingLotConstant;
use App\Model\ParkingSlots;
use Exception,DB;
class BookingHelper 
{

    public static function fetchAllAvailableParkingSlot(){

        return ParkingSlots::where('status',"vacant")->select('name','type')->get();

    }

    public static function fetchAllOccupiedParkingSlot() {
        return ParkingSlots::whereIn('status',["parked","booked"])->select('name','type')->get();
    }


    public static function fetchAllRegisteredUser(){
        return BookingLog::select('vehiclenumber','drivertype')->groupBy('vehiclenumber')->get();
    }

    public static function advanceBooking($request){

        $bookingLogcheck = BookingLog::where('vehiclenumber',$request['vehiclenumber'])->whereIn('status',['pending','parked'])->first();

        if($bookingLogcheck){
            throw new Exception("Vehicle Already have Alloted space");
        }

        $parkingSlotsId = BookingHelper::updateParkingSlots($request['type'],'booked');
        
        if($parkingSlotsId == 0){
            throw new Exception("No space Available");
        }
        $parkingNumber = BookingHelper::insertBookingLog($request,$parkingSlotsId,'pending');
        return $parkingNumber;
    }


    public static function updateParkingSlots($type,$status) {

        $parkingSlotsId = BookingHelper::fetchParkingSlotId($type);
        if($parkingSlotsId == 0){
            return $parkingSlotsId;
        }

        ParkingSlots::where('id',$parkingSlotsId)->update(['status'=>'booked']);
        return $parkingSlotsId;
    }

    public static function fetchParkingSlotId($type){
        
        if($type == 'reserved'){
           $ParkingSlots = ParkingSlots::where('status','vacant')->where('type','reserved')->first();

           if($ParkingSlots){
               return $ParkingSlots->id;
           }
        }

        $ParkingSlots = ParkingSlots::where('status','vacant')->where('type','general')->first();

           if($ParkingSlots){
               return $ParkingSlots->id;
           }
           
            return 0;
    }

    public static function insertBookingLog($request, $parkingSlotsId, $status){

        $parkingNumber = 'G2G'.random_int(100000, 999999);

        $bookingLog = New BookingLog;

        $bookingLog->vehiclenumber      = $request['vehiclenumber'];
        $bookingLog->parkingnumber      = $parkingNumber;
        $bookingLog->parkingspacetype   = $request['type'];
        $bookingLog->parkingslotsid     = $parkingSlotsId;
        $bookingLog->drivertype         = $request['driver_type'];
        $bookingLog->status             = $status;

        if($status == 'parked') {
            $bookingLog->bookingtime = date("Y-m-d H:i:s");
        }

        $bookingLog->save();

        return $parkingNumber;

    }


    public static function bookingSlot($request) {
        
        
        if(empty($request['parking_number'])){
            $parkingSlotsId = BookingHelper::updateParkingSlots($request['type'],'parked');
            if($parkingSlotsId == 0){
                throw new Exception("No space Available");
            }
            $parkingNumber = BookingHelper::insertBookingLog($request,$parkingSlotsId,'parked');
            return $parkingNumber;
        }
        else{
            $status = BookingHelper::updatedBookingLog($request['parking_number'],'parked');
            if($status == 0 ){
                $parkingSlotsId = BookingHelper::updateParkingSlots($request['type'],'parked');
                if($parkingSlotsId == 0){
                    throw new Exception("No space Available");
                }
                $parkingNumber = BookingHelper::insertBookingLog($request,$parkingSlotsId,'parked');
                return $parkingNumber;
            }
            return $request['parking_number'];
        }
    }

    public static function updatedBookingLog($parking_number,$status) {
        $bookingLog = BookingLog::where('parkingnumber',$parking_number)->select('id',DB::raw('TIMESTAMPDIFF(MINUTE,created_at,NOW()) as time_diff'))->first();

        $totalAvailableSlot = BookingHelper::fetchAllAvailableParkingSlot();

        if(($bookingLog->time_diff <=30 && $totalAvailableSlot->count() >60) || $bookingLog->time_diff <= 15 ) {
            BookingLog::where('parkingnumber',$parking_number)->update(['status'=>$status,'bookingtime'=>date("Y-m-d H:i:s")]);
            return 1;
        }
        return 0;
        
    }
}