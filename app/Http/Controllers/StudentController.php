<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EditStudentDetailsRequest;
use App\Http\Requests\Tutor\EditPersonalDetailsRequest;
use App\Models\Booking;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    //NORMAL USER METHODS INSERT HERE
    public function createStudent($validatedDataWithUserId)
    {
        $student = Student::create($validatedDataWithUserId);
        return $student;
    }

    public function deleteStudentAccount()
    {
        $user = Auth::user();

        User::destroy($user->id);
        return response()->json(['message' => 'Student deleted successfully.']);
    }


    public function getStudentDetails()
    {
        $user = Auth::user();
        $studentId = $user->student->id;

        $student = Student::where('id', $studentId)->first();

        return response()->json([
            'message' => 'Student retrieved successfully.',
            'student' => $student,
        ]);
    }

    public function editPersonalDetails(EditStudentDetailsRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $student = $user->student;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $validatedData['profile_image'] = asset('storage/' . $imagePath);
        }

        $student->update($validatedData);

        return response()->json([
            'message' => 'Student updated successfully.',
            'student' => $student,
        ]);
    }


    public function showSentTutorRequests(Request $request)
    {
        $tab = $request->query('tab', 'all');
    
        $user = Auth::user();
        $student = $user->student;
    
        if ($tab === 'all') {
            $StudentRequests = Booking::with('tutor')
                ->where('student_id', $student->id)
                ->whereNot('status', 'Ongoing')
                ->whereNot('status', 'Canceled')
                ->orderBy('updated_at', 'desc') 
                ->orderBy('created_at', 'desc') 
                ->paginate(6);
        }
        if ($tab === 'pending') {
            $StudentRequests = Booking::with('tutor')
                ->where('student_id', $student->id)
                ->where('status', 'Pending')
                ->orderBy('updated_at', 'desc') 
                ->orderBy('created_at', 'desc') 
                ->paginate(6);
        }
        if ($tab === 'completed') {
            $StudentRequests = Booking::with('tutor')
                ->where('student_id', $student->id)
                ->where('status', 'Completed')
                ->orderBy('updated_at', 'desc') 
                ->orderBy('created_at', 'desc') 
                ->paginate(6);
        }
    
        return response()->json([
            'message' => 'Accepted tutors retrieved successfully.',
            'sent_requests' => $StudentRequests,
        ]);
    }
    

    public function showStudentBookRequestDetails($book_id)
    {
        $bookDetails = Booking::where('id', $book_id)
            ->with('tutor', 'messages.dates')
            ->first();

        return response()->json([
            'message' => 'Student Bookings retrieved successfully.',
            'book_details' => $bookDetails,
        ]);
    }





    //ADMIN METHODS INSERT HERE
    public function showAllStudents()
    {
        $students = Student::all();

        return response()->json([
            'message' => 'Students retrieved successfully.',
            'all_students' => $students,
        ]);
    }
}
