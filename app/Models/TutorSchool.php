<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSchool extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutor_id',
        'image',
        'name',
        'course',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
