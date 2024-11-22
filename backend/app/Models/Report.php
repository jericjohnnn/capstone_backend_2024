<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'complainant_id',
        'offender_id',
        'title',
        'message',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function complainant()
    {
        return $this->belongsTo(User::class, 'complainant_id');
    }

    public function offender()
    {
        return $this->belongsTo(User::class, 'offender_id');
    }
}
