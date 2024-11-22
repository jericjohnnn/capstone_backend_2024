<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        // Randomly pick a user and check their user_type
        $user = User::inRandomOrder()->first(); // Random existing user
        $userType = $user->user_type;

        // Generate notification message based on user type
        return [
            'user_id' => $user->id,
            'message' => $this->generateNotificationMessage($userType),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Modify this method to return different messages for students and tutors
    private function generateNotificationMessage($userType)
    {
        $studentMessages = [
            'You have a new tutoring session request.',
            'Your next tutoring session is scheduled for tomorrow.',
            'Your homework has been reviewed by your tutor.',
            'Your tutor has sent you a message. Check your inbox.',
            'Your tutoring session has been completed. Please rate your tutor.',
            'A new tutor is available for your next session.',
            'Reminder: Your tutoring session starts in 15 minutes.',
            'You have a new notification from your tutor.',
            'Your tutor has rated your session. Check the feedback.',
            'Your tutoring session has been rescheduled.',
            'You have been assigned a new tutor. Review their profile.',
            'Your account is about to expire. Please renew your subscription.',
            'You have a new notification from the admin regarding your account.',
            'A tutor has completed their session with you. Please provide feedback.',
            'You’ve been given additional resources for your tutoring sessions.',
            'Your tutor has updated their availability. Check the new schedule.',
            'Your tutoring request has been approved.',
            'Your profile has been approved. You can now book tutors.',
            'A tutor has marked you as their favorite student. Keep it up!',
            'Your tutor has sent a message about your session progress.',
        ];

        $tutorMessages = [
            'You have a new tutoring session request from a student.',
            'Your next tutoring session is scheduled for tomorrow at 3 PM.',
            'A student has completed their homework. Please review it.',
            'Your student has sent you a message. Check your inbox for more details.',
            'Your tutoring session has been marked as completed. Thank you for your work!',
            'A new student has enrolled in the platform and is looking for a tutor.',
            'Reminder: Your tutoring session starts in 15 minutes.',
            'Your student has rated the session. Check the feedback!',
            'A new tutor has been assigned to assist you. Welcome aboard!',
            'Your account is about to expire. Please renew your subscription.',
            'You have a new notification from the admin regarding your account.',
            'A student has requested an urgent appointment. Please review it.',
            'Your recent tutoring session has been canceled by the student.',
            'New guidelines for tutors have been posted. Please review the updates.',
            'A student has marked you as their favorite tutor. Great job!',
            'Your student has rescheduled their tutoring session. Check the new timings.',
            'You’ve received a reward for your outstanding performance. Keep it up!',
            'There is a message from the admin regarding your recent activity.',
            'Your profile has been approved. You can now start tutoring students!',
            'You’ve been assigned a new student. Please review their profile and prepare.',
            'Your tutor account has been suspended due to policy violations.',
            'There are new resources available for your tutoring sessions. Check them out!',
            'Your request for additional tutoring hours has been approved.',
            'The admin has updated your tutoring availability. Please check your schedule.',
            'You have a pending student review. Please check your feedback section.',
            'The tutoring platform is undergoing maintenance. Some features may be unavailable.',
            'Your student has requested a follow-up session. Please confirm the timing.',
            'The admin has sent a message regarding new platform updates. Please review.',
        ];

        // Return different messages based on user type
        if ($userType == 1) {
            // Student notifications
            return $this->faker->randomElement($studentMessages);
        } else if ($userType == 2) {
            // Tutor notifications
            return $this->faker->randomElement($tutorMessages);
        }

        // Default fallback if no user_type matches
        return $this->faker->sentence();
    }
}
