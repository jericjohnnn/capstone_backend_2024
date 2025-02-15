<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Tutor extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'birthdate',
        'gender',
        'contact_number',
        'profile_image',
        'tutor_rate',
        'gmeet_link',
        'zoom_link',
        'skype_link',
        'biography',
        'school_id_number',
        'course',
        'year',
        'contacted_status',
        'offense_status',
        'approval_status'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'gender' => 'string',
        'course' => 'string',
        'year' => 'integer',
        'contacted_status' => 'boolean',
        'offense_status' => 'string',
        'approval_status' => 'string'
    ];

      // Laravel Scout searchable method
      public function toSearchableArray()
      {
          return [
              'first_name' => $this->first_name,
              'last_name' => $this->last_name,
          ];
      }

    //relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function workDays()
    {
        return $this->hasOne(TutorWorkDay::class);
    }

    public function complaints()
    {
        return $this->hasMany(Report::class, 'complaint_id', 'user_id');
    }

    public function offenses()
    {
        return $this->hasMany(Report::class, 'offender_id', 'user_id');
    }

    public function schools()
    {
        return $this->hasMany(TutorSchool::class);
    }

    public function certificates()
    {
        return $this->hasMany(TutorCertificate::class);
    }

    public function credentials()
    {
        return $this->hasMany(TutorCredential::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects');
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
