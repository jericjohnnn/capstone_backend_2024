<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDate extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_message_id',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    protected $touches = ['BookingMessage'];

    public function bookingMessage()
    {
        return $this->belongsTo(BookingMessage::class);
    }

    public static function hasConflict($startTime, $endTime, $tutorId, $studentId)
    {
        return self::whereHas('bookingMessage.booking', function ($query) use ($tutorId, $studentId) {
                $query->where(function ($q) use ($tutorId, $studentId) {
                    $q->where('tutor_id', $tutorId)
                      ->orWhere('student_id', $studentId);
                })
                ->where('status', 'Ongoing');
            })
            ->where(function ($query) use ($startTime, $endTime) {
                // Check for time slot overlaps only
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }
}
