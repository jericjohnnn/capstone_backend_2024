<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ongoing-tutor-booking-dates/{tutor_id}', [BookingController::class, 'getOngoingTutorBookingDatesById']);
    Route::get('/ongoing-student-booking-dates/{student_id}', [BookingController::class, 'getOngoingStudentBookingDatesById']);
    Route::get('/booking-schedules', [BookingController::class, 'getAllBookingSchedules']);

    Route::post('/create-booking', [BookingController::class, 'createBooking']);
    Route::post('/negotiate-booking/{booking_id}', [BookingController::class, 'negotiateBooking']);

    Route::patch('/update-student-booking/{book_id}', [BookingController::class, 'updateStudentBookRequestStatus']);
    Route::patch('/test/{test_id}', [BookingController::class, '']);

    Route::delete('/test/{book_id}', [BookingController::class, '']);
});
Route::get('/bookings/{bookingId}/tutor-request-details', [BookingController::class, 'showBookingDetails']);
Route::get('/bookings/all-tutor-request-bookings', [BookingController::class, 'showAllBookings']);

