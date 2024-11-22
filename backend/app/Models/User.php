<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type_id',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //relationships
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }
    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function complainantReports()
    {
        return $this->hasMany(Report::class, 'complainant_id');
    }

    public function offenderReports()
    {
        return $this->hasMany(Report::class, 'offender_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
