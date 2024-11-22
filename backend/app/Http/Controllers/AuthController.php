<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'user_type_id' => $validatedData['user_type_id'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $validatedDataWithUserId = array_merge($validatedData, ['user_id' => $user->id]);

        $student = 1;
        $tutor = 2;
        $userType = null;
        $userData = null;

        if ($validatedData['user_type_id'] ==  $student) {
            $studentData = (new StudentController)->createStudent($validatedDataWithUserId);
            $userType = "Student";
            $userData = $studentData;
        }
        if ($validatedData['user_type_id'] == $tutor) {
            $tutorData = (new TutorController)->createTutor($validatedDataWithUserId);
            $userType = "Tutor";
            $userData = $tutorData;
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'user_email' => $user->email,
            'user_full_name' => "{$validatedData['first_name']} {$validatedData['last_name']}",
            'user_type' => $userType,
            'user_data' => $userData,
            'token' => $token,
        ], 201);
    }


    public function userLogin(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401); // Unauthorized
        }

        $token = $user->createToken('authToken')->plainTextToken;

        $userType = null;
        $userFullName = null;
        $userData = null;

        if ($user->user_type_id === 1) {
            $student = Student::where('user_id', $user->id)->first();
            $userFullName = "{$student->first_name} {$student->last_name}";
            $userType = "Student";
            $userData = $student;
        }
        if ($user->user_type_id === 2) {
            $tutor = Tutor::where('user_id', $user->id)
            ->with('workDays', 'schools', 'certificates', 'subjects', 'ratings.student:id,first_name,last_name,profile_image')
            ->first();
            $userFullName = "{$tutor->first_name} {$tutor->last_name}";
            $userType = "Tutor";
            $userData = $tutor;
        }

        return response()->json([
            'message' => 'Login successful!',
            'user_email' => $user->email,
            'user_full_name' => $userFullName,
            'user_type' => $userType,
            'user_data' => $userData,
            'token' => $token,
        ], 200);
    }


    public function adminLogin(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $admin = Admin::where('email', $validatedData['email'])->first();

        if (!$admin || !Hash::check($validatedData['password'], $admin->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401); // Unauthorized
        }

        $token = $admin->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful!',
            'user_email' => $admin->email,
            'user_full_name' => $admin->name,
            'user_type' => "Admin",
            'token' => $token,
        ], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
