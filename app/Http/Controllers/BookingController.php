<?php

namespace App\Http\Controllers;

use App\Mail\BookingStore;
use App\Mail\ConfimBooking;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Product;
use App\Models\Slip;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $orderByColumn = $request->input('order_by_column', 'id');
        $orderDirection = $request->input('order_direction', 'asc');

        if ($orderDirection == 'desc') {
            $bookings = Booking::with('product', 'user', 'booking_items')
                ->orderBy($orderByColumn, 'desc')
                ->paginate($limit, '*', 'page');
        } else {
            $bookings = Booking::with('product', 'user', 'booking_items')
                ->orderBy($orderByColumn, 'asc')
                ->paginate($limit, '*', 'page');
        }

        $bookings->transform(function ($booking) {
            $temp = $booking->toArray();
            unset($temp['product_id']);
            unset($temp['user_id']);

            $temp['product'] = $booking->product ? $booking->product->toArray() : null;
            $temp['user'] = $booking->user ? $booking->user->toArray() : null;
            $temp['booking_items'] = $booking->booking_items ? $booking->booking_items->toArray() : [];

            return $temp;
        });

        return ['bookings' => $bookings];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {

        $product_id = $request->all()['product_id'];
        $user_id = $request->all()['user_id'];
        $product = Product::find($product_id);
        $user = User::find($user_id);
        $stock = $product['stock'];

        if ($stock > 0) {
            $booking = new Booking();
            $booking->fill($request->all());

            if ($request->hasFile('slip')) {
                $file = $request->file('slip');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('products'), $filename);
                $booking->slip = url('products/' . $filename);
            }
            $booking->save();
            // $bookingItemsString = $request->input('booking_items');
            // $bookingItemsArray = json_decode($bookingItemsString, true);

            // // dd("aa", $bookingItemsArray);
            // foreach ($bookingItemsArray as $item) {
            //     $bookingItem = new BookingItem();
            //     $bookingItem->fill($item);
            //     $bookingItem->booking_id = $booking->id;
            //     if ($item->hasFile('passport_img')) {
            //         $file = $item->file('passport_img');
            //         $extension = $file->getClientOriginalExtension();
            //         $filename = time() . '.' . $extension;
            //         $file->move(public_path('passport'), $filename);
            //         $booking->passport_img = url('passport/' . $filename);
            //     }
            //     $bookingItem->save();
            // }

            // email code starts from here
            $toEmail = 'asifakram74@gmail.com';
            $name = $user->name;
            $toAirline = $product->airlines;
            $tostock = $product->stock;
            $subject = "Notification of New Booking Received";
            $message = "Congratulations! You've recived a new booking.";
            // dd("aa", $message, $subject, $name,  $toAirline, $toEmail);

            Mail::to($toEmail)->send(new BookingStore($message, $subject, $name,  $toAirline, $toEmail, $tostock));


            return response()->json($booking, 201);
        } else {

            return [
                'status' => 500,
                'essage' => 'Product Stock is not available'
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $booking = Booking::with('product', 'user', 'booking_items')->find($id);

        $output = $booking->toArray();
        unset($output['product_id']);
        unset($output['user_id']);
        $output['product'] = $booking->product->toArray();
        $output['user'] = $booking->user->toArray();
        $output['booking_items'] = $booking->booking_items->toArray();
        return response()->json($output);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        $status = $booking->status;
        $user = User::find($booking->user_id);
        $product = Product::findOrFail($booking->product_id);

        if ($request->file) {
            if ($booking->slip) {
                unlink(public_path($booking->slip));
            }
            $file = $request->file('slip');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('products'), $filename);
            $path = asset('products/' . $filename);
            $booking->slip = $path;
        }

        $booking->fill($request->all());
        if ($request->status == 'Confirm' && $status == 'Pending') {
            // Send verification email
            // email code starts from here
            $toEmail = $user['email'];
            $name = $user['name'];
            $subject = "Booking Confirmed - Sidanah Travel";
            $message = "Your Booking is confirmed";
            Mail::to($toEmail)->send(new ConfimBooking($subject, $name, $message));
        }

        if ($request->status == 'Reject' && $status  == 'Pending') {
            if ($product) {
                $product->update([
                    'stock' => $product->stock + $booking->booking_seats
                ]);
            }
            $toEmail = $user['email'];
            $name = $user['name'];
            $subject = "Booking Reject - Sidanah Travel";
            $message = "Your Booking is Reject";
            Mail::to($toEmail)->send(new ConfimBooking($subject, $name, $message));
        }


        $booking->save();

        return response()->json($booking, 200);
    }
    
    public function getUserBookings(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $orderByColumn = $request->input('order_by_column', 'id');
        $orderDirection = $request->input('order_direction', 'asc');
        $userId = Auth::user()['id'];
        if ($orderDirection == 'desc') {
            $bookings = Booking::with('product', 'user', 'booking_items')
                ->where('user_id', $userId)
                ->orderBy($orderByColumn, 'desc')
                ->paginate($limit, '*', 'page');
        } else {
            $bookings = Booking::with('product', 'user', 'booking_items')
                ->where('user_id', $userId)
                ->orderBy($orderByColumn, 'asc')
                ->paginate($limit, '*', 'page');
        }

        $bookings->transform(function ($booking) {
            $temp = $booking->toArray();
            unset($temp['product_id']);
            unset($temp['user_id']);

            $temp['product'] = $booking->product ? $booking->product->toArray() : null;
            $temp['user'] = $booking->user ? $booking->user->toArray() : null;
            $temp['booking_items'] = $booking->booking_items ? $booking->booking_items->toArray() : [];

            return $temp;
        });

        return ['bookings' => $bookings];
    }

    public function getIndUserBooking(Request $request, $id)
{
    // dd('aaa',$id);
    $userId = $id; // Retrieve user_id from request

    // Ensure user_id is provided
    if (!$userId) {
        return response()->json(['error' => 'User ID is required'], 400);
    }

    // Fetch all bookings for the specified user
    $bookings = Booking::with('product', 'user', 'booking_items')
        ->where('user_id', $userId)
        ->get();
    // Transform the bookings
    $bookings = $bookings->transform(function ($booking) {
        $temp = $booking->toArray();
        unset($temp['product_id']);
        unset($temp['user_id']);

        $temp['product'] = $booking->product ? $booking->product->toArray() : null;
        $temp['user'] = $booking->user ? $booking->user->toArray() : null;
        $temp['booking_items'] = $booking->booking_items ? $booking->booking_items->toArray() : [];

        return $temp;
    });

    return response()->json(['bookings' => $bookings]);
}


}
