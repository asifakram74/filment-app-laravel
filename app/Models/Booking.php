<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Booking extends Model
{           
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'booking_seats',
        'slip',
        'status'
    ];


    protected $with = ['booking_items'];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($booking) {
        if (isset($booking['booking_seats']) && $booking['booking_seats'] > 0) {
            $product = Product::findOrFail($booking['product_id']);
            if ($product) {
                $product->update([
                    'stock' => $product['stock'] - $booking['booking_seats']
                ]);
            }
        }
    });
}




    // Define the relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define the relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship
    public function booking_items()
    {
        return $this->hasMany(BookingItem::class);
    }
}
