<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorWorkDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutor_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
