<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* getAllAvailableParkingSlot
getAllOccupiedParkingSlot
getAllRegisteredUser

advanceBookingSlot
advanceBookingSlot

updateAllLateBooking
 */
Route::get('fetch-all-available-parking-slot',"BookingController@getAllAvailableParkingSlot");
Route::get('fetch-all-occupied-parking-slot',"BookingController@getAllOccupiedParkingSlot");
Route::get('fetch-all-registered-user',"BookingController@getAllRegisteredUser");


Route::post('advance-booking-slot',"BookingController@advanceBookingSlot");
Route::post('booking-slot',"BookingController@bookingSlot");

Route::get('update-all-late-booking',"BookingController@updateAllLateBooking");

