<?php

namespace App\Http\Controllers;

use App\Events\PendingBookAccepted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Requests\Booking\NegotiateBookingRequest;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingMessage;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function createBooking(BookingRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();
        $studentId = $user->student->id;

        // Check for schedule conflicts for all proposed dates
        foreach ($validatedData['selected_date_times'] as $dateTime) {
            if (BookingDate::hasConflict(
                $dateTime['start'],
                $dateTime['end'],
                $validatedData['tutor_id'],
                $studentId
            )) {
                return response()->json([
                    'message' => 'Schedule conflict detected. Please select different time slots.',
                    'conflicting_datetime' => [
                        'start' => $dateTime['start'],
                        'end' => $dateTime['end']
                    ]
                ], 422);
            }
        }

        $booking = Booking::create($validatedData);

        $bookingMessage = BookingMessage::create([
            'booking_id' => $booking->id,
            'title' => $validatedData['message_title'],
            'message' => $validatedData['message_content'],
        ]);

        foreach ($validatedData['selected_date_times'] as $dateTime) {
            BookingDate::create([
                'booking_message_id' => $bookingMessage->id,
                'start_time' => $dateTime['start'],
                'end_time' => $dateTime['end'],
            ]);
        }

        return response()->json([
            'message' => 'Booking requested successfully.',
        ]);
    }

    public function updateStudentBookRequestStatus(Request $request, $book_id)
    {
        $booking = Booking::findOrFail($book_id);

        $user = Auth::user();
        $userToBeNotified = null;
        if ($user->student) {
            $tutor = Tutor::find($booking->tutor_id);
            $userToBeNotified = User::find($tutor->user_id);
            $user = $user->student;
        } else if ($user->tutor) {
            $student = Student::find($booking->student_id);
            $userToBeNotified = User::find($student->user_id);
            $user = $user->tutor;
        }

        // Only check for conflicts if the status is being updated to 'Ongoing'
        if ($request['status'] === 'Ongoing') {
            $latestMessage = $booking->messages()->latest()->first();
            if (!$latestMessage) {
                return response()->json([
                    'message' => 'No booking dates found.',
                ], 422);
            }

            // Get all existing ongoing bookings' dates for both tutor and student
            foreach ($latestMessage->dates as $newDate) {
                if (BookingDate::hasConflict(
                    $newDate->start_time,
                    $newDate->end_time,
                    $booking->tutor_id,
                    $booking->student_id
                )) {
                    return response()->json([
                        'message' => 'Cannot update status. Schedule conflict detected.',
                        'conflicting_datetime' => [
                            'start' => $newDate->start_time,
                            'end' => $newDate->end_time
                        ]
                    ], 422);
                }
            }
        }
        PendingBookAccepted::dispatch($user, $userToBeNotified);
        $booking->status = $request['status'];
        $booking->save();

        return response()->json([
            'message' => 'Booking status updated successfully.',
            'booking' => $booking
        ]);
    }

    public function getOngoingTutorBookingDatesById($tutor_id)
    {
        $bookings = Booking::with(['messages.dates'])
                ->where('tutor_id', $tutor_id)
                ->where('status', 'Ongoing')
                ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => 'Booking not found or has no ongoing bookings.',
            ]);
        }

        $bookingsData = $bookings->map(function ($booking) {
            // Get the last message by sorting messages by created_at
            $lastMessage = $booking->messages->sortByDesc('created_at')->first();

            return [
                'booking_id' => $booking->id,
                'subject' => $booking->subject,
                'booking_dates' => $lastMessage ? $lastMessage->dates : [],
            ];
        });

        return response()->json([
            'message' => 'Tutor booking dates retrieved successfully.',
            'tutor_bookings' => $bookingsData,
        ]);
    }

    public function getOngoingStudentBookingDatesById($student_id)
    {
        $bookings = Booking::with(['messages.dates'])
                ->where('student_id', $student_id)
                ->where('status', 'Ongoing')
                ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => 'Booking not found or has no ongoing bookings.',
            ]);
        }

        $bookingsData = $bookings->map(function ($booking) {
            // Get the last message by sorting messages by created_at
            $lastMessage = $booking->messages->sortByDesc('created_at')->first();

            return [
                'booking_id' => $booking->id,
                'subject' => $booking->subject,
                'booking_dates' => $lastMessage ? $lastMessage->dates : [],
            ];
        });

        return response()->json([
            'message' => 'Student booking dates retrieved successfully.',
            'student_bookings' => $bookingsData,
        ]);
    }


    public function getAllBookingSchedules()
    {
        $user = Auth::user();

        if ($user->tutor) {
            $bookings = Booking::with(['messages.dates'])
                ->where('tutor_id', $user->tutor->id)
                ->where('status', 'Ongoing')
                ->get();
        }
        if ($user->student) {
            $bookings = Booking::with(['messages.dates'])
                ->where('student_id', $user->student->id)
                ->where('status', 'Ongoing')
                ->get();
        }

        if ($bookings->isEmpty()) {
            return response()->json([
                'message' => 'Booking not found or has no bookings.',
            ]);
        }

        $bookingsData = $bookings->map(function ($booking) {
            // Get the last message by sorting messages by created_at
            $lastMessage = $booking->messages->sortByDesc('created_at')->first();

            return [
                'booking_id' => $booking->id,
                'subject' => $booking->subject,
                'booking_dates' => $lastMessage ? $lastMessage->dates : [],
            ];
        });

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookingsData,
        ]);
    }


    public function negotiateBooking(NegotiateBookingRequest $request, $booking_id)
    {
        $validatedData = $request->validated();
        $user = Auth::user();
        $booking = Booking::findOrFail($booking_id);

        // Check for schedule conflicts for all proposed dates
        foreach ($validatedData['selected_date_times'] as $dateTime) {
            if (BookingDate::hasConflict(
                $dateTime['start'],
                $dateTime['end'],
                $booking->tutor_id,
                $booking->student_id
            )) {
                return response()->json([
                    'message' => 'Schedule conflict detected. Please select different time slots.',
                    'conflicting_datetime' => [
                        'start' => $dateTime['start'],
                        'end' => $dateTime['end']
                    ]
                ], 422);
            }
        }

        $bookingMessage = BookingMessage::create([
            'booking_id' => $booking->id,
            'title' => $validatedData['message_title'],
            'message' => $validatedData['message_content'],
        ]);

        foreach ($validatedData['selected_date_times'] as $dateTime) {
            BookingDate::create([
                'booking_message_id' => $bookingMessage->id,
                'start_time' => $dateTime['start'],
                'end_time' => $dateTime['end'],
            ]);
        }

        if ($user->tutor) {
            $booking->load(['student', 'messages.dates']);
        }
        if ($user->student) {
            $booking->load(['tutor', 'messages.dates']);
        }

        return response()->json([
            'message' => 'Booking requested successfully.',
            'book_details' => $booking,
        ]);
    }

    // JJA GODS





    // DAVEN GODS
    public function showAllBookings()
    {
        $bookings = Booking::with('tutor.subjects', 'student')->get(); // Retrieve all bookings with tutor and subject details

        // Transform the collection to return only the needed details
        $bookings->transform(function ($booking) {
            $tutor = $booking->tutor;

            return [
                'id' => $booking->id,
                'tutor_name' => $tutor ? $tutor->first_name . ' ' . $tutor->last_name : null,
                'profile_image' => $tutor ? $tutor->profile_image : null,
                'subjects' => $tutor ? $tutor->subjects->pluck('name') : [],
                'booking_status' => $booking->status,
                'tutor_age' => $tutor && $tutor->birthdate ? \Carbon\Carbon::parse($tutor->birthdate)->age : null, // Calculate age
            ];
        });

        return response()->json([
            'message' => 'All bookings retrieved successfully.',
            'bookings' => $bookings,
        ]);
    }





    public function showBookingDetails($bookingId)
    {
        $booking = Booking::with(['tutor', 'tutor.subjects', 'messages.dates']) // Load related messages and dates
            ->where('id', $bookingId)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $tutor = $booking->tutor;
        $subjects = $tutor->subjects;

        // Prepare booking messages and dates
        $messages = $booking->messages->map(function ($message) {
            return [
                'title' => $message->title,
                'message' => $message->message,
                'dates' => $message->dates->map(function ($date) {
                    return [
                        'start_time' => $date->start_time->toDateTimeString(),
                        'end_time' => $date->end_time->toDateTimeString(),
                    ];
                }),
            ];
        });

        return response()->json([
            'tutor_name' => $tutor->first_name . ' ' . $tutor->last_name,
            'profile_image' => $tutor->profile_image,
            'contact_number' => $tutor->contact_number,
            'subjects' => $subjects->pluck('name'),
            'booking_status' => $booking->status,
            'location' => $booking->location,
            'learning_mode' => $booking->learning_mode,
            'online_meeting_platform' => $booking->online_meeting_platform,
            'created_at' => $booking->created_at->toDateTimeString(),
            'messages' => $messages,
        ]);
    }
}
