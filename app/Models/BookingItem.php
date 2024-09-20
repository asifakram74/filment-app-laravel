<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class BookingItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'booking_id',
        'type',
        'title',
        'fname',
        'lname',
        'passport',
        'passport_exp',
        'dob', 
        'passport_img', 
        'serial'
    ];

    // Define the relationship
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
