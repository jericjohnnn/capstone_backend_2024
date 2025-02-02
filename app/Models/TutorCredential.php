<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorCredential extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutor_id',
        'image',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
