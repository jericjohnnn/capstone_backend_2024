<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorCertificate extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutor_id',
        'image',
        'title',
        'issuer',
        'date_issued'
    ];

    protected $casts = [
        'date_issued' => 'date'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
