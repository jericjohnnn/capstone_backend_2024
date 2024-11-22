<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OffenseStatusRequest;
use App\Models\Notification;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //NORMAL USER METHODS INSERT HERE
   public function getUserNotifications()
{
    $user = Auth::user();

    // Get notifications by user_id
    $notifications = Notification::where('user_id', $user->id)->get();

    return response()->json([
        'message' => 'Notifications retrieved successfully.',
        'notifications' => $notifications,
    ]);
}








    //ADMIN METHODS INSERT HERE
    public function deleteUser($user_id)
    {
        User::destroy($user_id);
        return response()->json(['message' => 'User deleted successfully.']);
    }

    public function changeOffenseStatus(OffenseStatusRequest $request, $user_id)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($user_id);

        if ($user->user_type_id === 1) {
            Student::where('user_id', $user_id)->update(['offense_status' => $validatedData['offense_status']]);
        } elseif ($user->user_type_id === 2) {
            Tutor::where('user_id', $user_id)->update(['offense_status' => $validatedData['offense_status']]);
        }

        return response()->json(['message' => 'Offense status updated successfully.']);
    }
}
