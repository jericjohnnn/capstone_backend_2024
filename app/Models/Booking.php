<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutor_id',
        'student_id',
        'learning_mode',
        'location',
        'subject',
        'online_meeting_platform',
        'contact_number',
        'status'
    ];

    protected $casts = [
        'learning_mode' => 'string',
        'status' => 'string'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function messages()
    {
        return $this->hasMany(BookingMessage::class);
    }
     
}
