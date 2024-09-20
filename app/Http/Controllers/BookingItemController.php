<?php

namespace App\Http\Controllers;

use App\Models\BookingItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $bookingItem = new BookingItem();
        $bookingItem->booking_id = $booking_id;
        $bookingItem->fill($request->all());

        if ($request->hasFile('passport_img')) {
            $file = $request->file('passport_img');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('passport'), $filename);
            $bookingItem->passport_img = url('passport/' . $filename);
        }
        $bookingItem->save();
        return response()->json(['status' => 'success', 'data' => $bookingItem], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bookingItem = BookingItem::find($id);
        if ($bookingItem) {
            return $bookingItem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookingItem $bookingItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bookingItem = BookingItem::find($id); 


        if ($request->file) {
            $file = $request->file('passport_img');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('passport'), $filename);
            $path = asset('passport/' . $filename);
            $bookingItem->passport_img = $path;
        }

        if ($bookingItem) {
            $bookingItem->update($request->all());
            return [
                'bookingItemInfo' => $bookingItem,
                'status' => 200,
                'message' => 'Booking Item Update updated successfully'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'Product not found'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bookingItem = BookingItem::find($id);
        $bookingItem->delete();
        return [
            'productInfo' => $bookingItem,
            'tatus' => 200,
            'essage' => 'Booking Item deleted successfully'
        ];
    }


// Get single Booking ITem
public function getSingleBooking($id)
{
  
}

 
   

}
