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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\SendOtpMail;

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

        if ($validatedData['user_type_id'] == $student) {
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
            'user_id' => $user->id,
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
                ->with('workDays', 'schools', 'certificates', 'credentials', 'subjects', 'ratings.student:id,first_name,last_name,profile_image')
                ->first();
            $userFullName = "{$tutor->first_name} {$tutor->last_name}";
            $userType = "Tutor";
            $userData = $tutor;
            if ($tutor->approval_status === 'Rejected') {
                return response()->json([
                    'message' => 'Your account is unapproved. You can be interviewd again after 30 days.',
                ], 401);
            }
        }

        return response()->json([
            'message' => 'Login successful!',
            'user_id' => $user->id,
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


    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(2);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['otp' => $otp, 'expires_at' => $expiresAt, 'created_at' => now()]
        );

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(Request $request){
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        if (now()->greaterThan($reset->expires_at)) {
            return response()->json(['error' => 'OTP has expired. Please request a new one.'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);

        // Delete OTP record after reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successful']);
    }
}
