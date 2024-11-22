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
}
