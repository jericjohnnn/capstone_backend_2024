<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'title',
        'message'
    ];

    protected $touches = ['Booking'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function dates()
    {
        return $this->hasMany(BookingDate::class);
    }
}
