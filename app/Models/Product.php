<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory, HasApiTokens;


    protected $fillable = [
        'airlines',
        'price',
        'stock',
        'sector',
        'pnr',
        'dept_date', 
        'dept_day', 
        'dept_timing', 
        'des_arv_day',
        'des_arv_date', 
        'des_arv_timing', 
        'arv_date', 
        'arv_day', 
        'arv_timing',
        'return_back_day', 
        'return_back_date', 
        'return_back_timing', 
        'flight_number', 
        'return_flight_number',
        'category'    ];

    // Define the relationship
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
