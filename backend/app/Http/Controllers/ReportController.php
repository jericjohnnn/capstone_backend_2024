<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use App\Http\Requests\Admin\ReportStatusRequest;
use App\Models\Report;
use App\Models\Tutor;
use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //NORMAL USER METHODS INSERT HERE
    public function createReport(ReportRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['complainant_id'] = auth()->user()->id;

        if ($request->has('tutor_offender_id')) {
            $offender = Tutor::findOrFail($request->tutor_offender_id);
            $validatedData['offender_id'] = $offender->user_id;
        } elseif ($request->has('student_offender_id')) {
            $offender = Student::findOrFail($request->student_offender_id);
            $validatedData['offender_id'] = $offender->user_id;
        } else {
            return response()->json([
                'message' => 'No valid offender ID provided'
            ], 422);
        }

        $report = Report::create($validatedData);

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => $report
        ], 201);

    }









    //ADMIN METHODS INSERT HERE
    public function showAllReports()
    {
        $reports = Report::with('complainant.tutor', 'complainant.student', 'complainant.userType')->get();  // Use get() instead of paginate()

        $reports->transform(function ($report) {
            $complainant = $report->complainant;
            $first_name = $complainant->tutor?->first_name ?? $complainant->student?->first_name;
            $last_name = $complainant->tutor?->last_name ?? $complainant->student?->last_name;

            return [
                'id' => $report->id,
                'complainant_name' => $first_name && $last_name ? "$first_name $last_name" : null,
                'complainant_profile_image' => $complainant->tutor?->profile_image ?? $complainant->student?->profile_image,
                'complainant_user_type' => $complainant->userType->type,
                'report_status' => $report->status,
            ];
        });

        return response()->json([
            'message' => 'All reports retrieved successfully.',
            'complainant_report' => $reports,
        ]);
    }



    public function showReport($report_id)
    {
        $report = Report::where('id', $report_id)
            ->with(
                'complainant.tutor',
                'complainant.student',
                'complainant.userType',
                'offender.tutor',
                'offender.student',
                'offender.userType'
            )->first();

        $getUserData = function ($user) {
            $firstName = $user->tutor?->first_name ?? $user->student?->first_name ?? null;
            $lastName = $user->tutor?->last_name ?? $user->student?->last_name ?? null;

            return [
                'full_name' => $firstName && $lastName ? "$firstName $lastName" : null,
                'profile_image' => $user->tutor?->profile_image ?? $user->student?->profile_image ?? null,
                'user_type' => $user->userType->type,
                'offense_status' => $user->tutor?->offense_status ?? $user->student?->offense_status ?? null,
                'contact_number' => $user->tutor?->contact_number ?? $user->student?->contact_number ?? null,
                'address' => $user->tutor?->address ?? $user->student?->address ?? null,
            ];
        };

        $complainantData = $getUserData($report->complainant);
        $offenderData = $getUserData($report->offender);

        $report_data = [
            'message' => 'Report retrieved successfully.',
            'report_id' => $report->id,
            'report_title' => $report->title,
            'report_message' => $report->message,
            'complainant_name' => $complainantData['full_name'],
            'complainant_profile_image' => $complainantData['profile_image'],
            'complainant_user_type' => $complainantData['user_type'],
            'offender_name' => $offenderData['full_name'],
            'offender_profile_image' => $offenderData['profile_image'],
            'offender_user_type' => $offenderData['user_type'],
            'offender_offense_status' => $offenderData['offense_status'],
            'offender_contact_number' => $offenderData['contact_number'],
            'offender_address' => $offenderData['address'],
            'report_status' => $report->status,
        ];

        return response()->json([
            'message' => 'Report retrieved successfully.',
            'report_data' => $report_data,
        ]);
    }


    public function changeReportStatus(ReportStatusRequest $request, $report_id)
    {
        $validatedData = $request->validated();

        $report = Report::findOrFail($report_id);

        $report->status = $validatedData['status'];
        $report->save();

        return response()->json(['message' => 'Report status updated successfully.']);
    }
}
