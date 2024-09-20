<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldBookings = Booking::with('product')->whereDate('created_at', Carbon::yesterday())->where('status','Pending')->get();
        foreach ($oldBookings as $booking) {
            $booking->update(['status' => 'Cencel']);
            $product = Product::findOrFail($booking['product_id']);
            $product->update([
                'stock' => $product['stock'] + $booking['booking_seats']
            ]);
        }

    }
}
