<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApprovalStatusRequest;
use App\Http\Requests\Tutor\CertificateRequest;
use App\Http\Requests\Tutor\CredentialsRequest;
use App\Http\Requests\Tutor\EditPersonalDetailsRequest;
use App\Http\Requests\Tutor\SchoolsRequest;
use App\Http\Requests\Tutor\EditSubjectsRequest;
use App\Http\Requests\Tutor\EditWorkDaysRequest;
use App\Http\Requests\Tutor\SearchTutorRequest;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingMessage;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutorCertificate;
use App\Models\TutorCredential;
use App\Models\TutorSchool;
use App\Models\User;
use App\Services\ImgurService;
use Illuminate\Http\Request;
use App\Http\Requests\Tutor\RateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorController extends Controller
{
    protected $imgurService;

    public function __construct(?ImgurService $imgurService = null)
    {
        $this->imgurService = $imgurService;
    }

    //NORMAL USER METHODS INSERT HERE
    public function createTutor($validatedDataWithUserId)
    {
        $tutor = Tutor::create($validatedDataWithUserId);

        if (!empty($validatedDataWithUserId['images'])) {
            foreach ($validatedDataWithUserId['images'] as $image) {
                $imagePath = $image->getPathname();
                $imgurUrl = $this->imgurService->uploadImage($imagePath);

                if ($imgurUrl) {
                    $tutor->credentials()->create(['image' => $imgurUrl]);
                }
            }
        }

        $tutor->workDays()->create([]);

        if (isset($validatedDataWithUserId['subjects'])) {
            $tutor->subjects()->syncWithoutDetaching($validatedDataWithUserId['subjects']);
        }

        return $tutor->load(['subjects', 'certificates', 'credentials', 'schools', 'ratings.student:id,first_name,last_name,profile_image', 'workDays']);
    }

    public function showTutors(Request $request)
    {
        $number_of_tutors = $request->query('number_of_tutors', 5);
        $tutors = Tutor::where('approval_status', 'Accepted')
            ->whereNot('offense_status', 'Banned')
            ->withCount('schools')
            ->withCount('certificates')
            ->with('subjects:id,name,abbreviation', 'ratings:id,tutor_id,rate')
            ->orderByRaw('(SELECT COALESCE(AVG(rate) * COUNT(ratings.id), 0)
                           FROM ratings
                           WHERE ratings.tutor_id = tutors.id) DESC')
            ->orderByDesc('schools_count')
            ->orderByDesc('certificates_count')
            ->paginate($number_of_tutors);

        $tutors->transform(function ($tutor) {
            return [
                'id' => $tutor->id,
                'tutor_name' => "{$tutor->first_name} {$tutor->last_name}",
                'profile_image' => $tutor->profile_image,
                'tutor_subjects' => $tutor->subjects->map(function ($subject) {
                    return [
                        'name' => $subject->name,
                        'abbreviation' => $subject->abbreviation
                    ];
                }),
                'tutor_rating' => $tutor->ratings->avg('rate'),
            ];
        });

        return response()->json([
            'message' => 'Tutors retrieved successfully.',
            'tutor_previews' => $tutors,
        ]);
    }

    public function showTutorDetail($tutor_id)
    {
        $tutor = Tutor::where('id', $tutor_id)
            ->with('workDays', 'schools', 'certificates', 'credentials', 'subjects', 'ratings.student:id,first_name,last_name,profile_image')
            ->first();

        return response()->json([
            'message' => 'Tutor retrieved successfully.',
            'tutor' => $tutor,
        ]);
    }

    public function getTutorDetails()
    {
        $user = Auth::user();
        $tutorId = $user->tutor->id;

        $tutor = Tutor::where('id', $tutorId)
            ->with('workDays', 'schools', 'certificates', 'credentials', 'subjects', 'ratings.student:id,first_name,last_name,profile_image')
            ->first();

        return response()->json([
            'message' => 'Tutor retrieved successfully.',
            'tutor' => $tutor,
        ]);
    }

    public function showTutorSchedules($tutor_id)
    {
        // Retrieve all bookings for the given tutor with messages and dates
        $tutorBookings = Booking::with(['messages.dates'])
            ->where('tutor_id', $tutor_id)
            ->where('status', 'Ongoing')
            ->get();

        if ($tutorBookings->isEmpty()) {
            return response()->json([
                'message' => 'Tutor not found or has no bookings.',
            ]);
        }

        $bookingData = $tutorBookings->map(function ($booking) {
            // Get the last message by sorting messages by created_at
            $lastMessage = $booking->messages->sortByDesc('created_at')->first();

            return [
                'booking_id' => $booking->id,
                'subject' => $booking->subject,
                'booking_dates' => $lastMessage ? $lastMessage->dates : [],
            ];
        });

        return response()->json([
            'message' => 'Tutor bookings retrieved successfully.',
            'bookings' => $bookingData,
        ]);
    }

    public function editSubjects(EditSubjectsRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $tutor->subjects()->sync($validatedData['subjects']);
        $tutor->load(['subjects', 'certificates', 'schools', 'ratings.student:id,first_name,last_name,profile_image', 'workDays']);

        return response()->json([
            'message' => 'Tutor subjects updated successfully.',
            'tutor' => $tutor,
        ]);
    }

    public function editPersonalDetails(EditPersonalDetailsRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->getPathname();
            $imgurUrl = $this->imgurService->uploadImage($imagePath);
            if ($imgurUrl) {
                $validatedData['profile_image'] = $imgurUrl;
            }
        }

        $tutor->update($validatedData);
        $tutor->load(['subjects', 'certificates', 'schools', 'ratings.student:id,first_name,last_name,profile_image', 'workDays']);

        return response()->json([
            'message' => 'Tutor updated successfully.',
            'tutor' => $tutor,
        ]);
    }

    public function editWorkDays(EditWorkDaysRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;
        $workDays = $tutor->workDays;

        $workDays->update($validatedData);

        return response()->json([
            'message' => 'Tutor work days updated successfully.',
            'work_days' => $workDays,
        ]);
    }

    public function editSchool(SchoolsRequest $request, $school_id)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $school = $tutor->schools()->where('id', $school_id)->first();

        if (!$school) {
            return response()->json([
                'message' => 'School not found for this tutor.'
            ], 404);
        }

        $school->update($validatedData);

        return response()->json([
            'message' => 'School updated successfully.',
            'school' => $school,
        ]);
    }


    public function createSchool(SchoolsRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $school = $tutor->schools()->create($validatedData);

        return response()->json([
            'message' => 'School created successfully.',
            'school' => $school,
        ]);
    }


    public function deleteSchool($school_id)
    {
        $user = Auth::user();
        $tutor = $user->tutor;

        $school = TutorSchool::where('id', $school_id)->where('tutor_id', $tutor->id)->first();

        if (!$school) {
            return response()->json([
                'message' => 'School not found for this tutor.'
            ], 404);
        }

        $school->delete();

        return response()->json([
            'message' => 'School deleted successfully.',
            'school' => $school,
        ]);
    }

    public function createCredential(CredentialsRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $imagePath = $request->file('image')->getPathname();
        $imgurUrl = $this->imgurService->uploadImage($imagePath);

        if ($imgurUrl) {
            $validatedData['image'] = $imgurUrl;
        }

        $credential = $tutor->credentials()->create($validatedData);

        return response()->json([
            'message' => 'Credential created successfully.',
            'credential' => $credential,
        ]);
    }

    public function deleteCredential($credential_id)
    {
        $user = Auth::user();
        $tutor = $user->tutor;

        $credential = TutorCredential::where('id', $credential_id)->where('tutor_id', $tutor->id)->first();

        if (!$credential) {
            return response()->json([
                'message' => 'Credential not found for this tutor.'
            ], 404);
        }

        $credential->delete();

        return response()->json([
            'message' => 'Certificate deleted successfully.',
            'credential' => $credential,
        ]);
    }

    public function createCertificate(CertificateRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $certificate = $tutor->certificates()->create($validatedData);

        return response()->json([
            'message' => 'Certificate created successfully.',
            'certificate' => $certificate,
        ]);
    }


    public function editCertificate(CertificateRequest $request, $certificate_id)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        $tutor = $user->tutor;

        $certificate = $tutor->certificates()->where('id', $certificate_id)->first();

        if (!$certificate) {
            return response()->json([
                'message' => 'Certificate not found for this tutor.'
            ], 404);
        }

        $certificate->update($validatedData);

        return response()->json([
            'message' => 'Certificate updated successfully.',
            'certificate' => $certificate,
        ]);
    }


    public function deleteCertificate($certificate_id)
    {
        $user = Auth::user();
        $tutor = $user->tutor;

        $certificate = TutorCertificate::where('id', $certificate_id)->where('tutor_id', $tutor->id)->first();

        if (!$certificate) {
            return response()->json([
                'message' => 'Certificate not found for this tutor.'
            ], 404);
        }

        $certificate->delete();

        return response()->json([
            'message' => 'Certificate deleted successfully.',
            'certificate' => $certificate,
        ]);
    }

    public function showStudentRequests(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $user = Auth::user();
        $tutor = $user->tutor;

        if ($tab === 'pending') {
            $StudentRequests = Booking::with('student')
                ->where('tutor_id', $tutor->id)
                ->where('status', 'Pending')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(6);
        }
        if ($tab === 'completed') {
            $StudentRequests = Booking::with('student')
                ->where('tutor_id', $tutor->id)
                ->where('status', 'Completed')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(6);
        }

        return response()->json([
            'message' => 'Accepted tutors retrieved successfully.',
            'student_requests' => $StudentRequests,
        ]);
    }

    public function searchTutors(SearchTutorRequest $request)
    {
        $validatedData = $request->validated();

        // From URL query
        $tutor = $validatedData['tutor'] ?? '';
        $subject = $validatedData['subject'] ?? '';

        $searchedSubject = Subject::search($subject)->get();
        $subjectNames = $searchedSubject->pluck('name')->toArray();

        $tutorIds = Tutor::whereHas('subjects', function ($query) use ($subjectNames) {
            $query->whereIn('subjects.name', $subjectNames);
        })->pluck('id')->toArray();

        $tutors = Tutor::search($tutor)
            ->whereIn('id', $tutorIds)
            ->whereIn('approval_status', ['Accepted'])
            ->whereNotIn('offense_status', ['Banned'])
            ->orderBy('created_at', 'asc')
            ->paginate(5);

        if ($tutors->isEmpty()) {
            return response()->json($tutors);
        }

        $tutors->load('subjects:id,name,abbreviation', 'ratings:id,tutor_id,rate');

        $tutors->transform(function ($tutor) {
            return [
                'id' => $tutor->id,
                'tutor_name' => "{$tutor->first_name} {$tutor->last_name}",
                'profile_image' => $tutor->profile_image,
                'tutor_subjects' => $tutor->subjects->map(function ($subject) {
                    return [
                        'name' => $subject->name,
                        'abbreviation' => $subject->abbreviation,
                    ];
                }),
                'tutor_rating' => $tutor->ratings->avg('rate'),
            ];
        });

        return response()->json($tutors);
    }

    public function showBookRequestDetails($book_id)
    {
        $bookDetails = Booking::where('id', $book_id)
            ->with([
                'student',
                'messages.dates',
                'tutor:id,gmeet_link,zoom_link,skype_link'
            ])
            ->first();

        return response()->json([
            'message' => 'Tutor retrieved successfully.',
            'book_details' => $bookDetails,
        ]);
    }


    public function deleteTutorAccount()
    {
        $user = Auth::user();

        User::destroy($user->id);
        return response()->json(['message' => 'Tutor deleted successfully.']);
    }


    public function rateTutor(RateRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['student_id'] = auth()->user()->id;
        $validatedData['tutor_id'] = $request->tutor_id;

        $rating = Rating::create($validatedData);

        return response()->json([
            'message' => 'Rating created successfully.',
            'rating' => $rating,
        ]);
    }


    //ADMIN METHODS INSERT HERE
    public function showAllTutors(Request $request)
    {
        $search = $request->input('search', '');

        $tutors = Tutor::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->with('credentials')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Tutors retrieved successfully.',
            'all_tutors' => $tutors,
        ]);
    }

    public function showAcceptedTutors()
    {
        $acceptedTutors = Tutor::where('approval_status', 'Accepted')->paginate(8);

        return response()->json([
            'message' => 'Accepted tutors retrieved successfully.',
            'accepted_tutors' => $acceptedTutors,
        ]);
    }

    public function changeApprovalStatus(ApprovalStatusRequest $request, $tutor_id)
    {
        $validatedData = $request->validated();

        $tutor = Tutor::findOrFail($tutor_id);

        $tutor->approval_status = $validatedData['approval_status'];
        $tutor->save();

        return response()->json(['message' => 'Approval status updated successfully.']);
    }

    public function changeContactedStatus(Request $request, $tutor_id)
    {
        $tutor = Tutor::findOrFail($tutor_id);

        $tutor->contacted_status = $request['contacted_status'];
        $tutor->save();

        return response()->json(['message' => 'Contacted status updated successfully.']);
    }
}
