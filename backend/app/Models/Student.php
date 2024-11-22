<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'birthdate',
        'contact_number',
        'profile_image',
        'school_id_number',
        'grade_year',
        'offense_status'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    //relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complaints()
    {
        return $this->hasMany(Report::class, 'complaint_id', 'user_id');
    }

    public function offenses()
    {
        return $this->hasMany(Report::class, 'offender_id', 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
