<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slip;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  
     public function index(Request $request)
     {
         $page = $request->input('page', 1);
         $limit = $request->input('limit', 10);
         $orderBy = $request->input('order_by', 'id');
         $orderDirection = $request->input('order_direction', 'asc');
         $stockFilter = $request->input('stock_filter', '>');
     
         $validColumns = ['id', 'name', 'price', 'stock']; // list of valid columns
         $validOperators = ['>', '<', '=']; // list of valid operators
     
         if (!in_array($orderBy, $validColumns)) {
             $orderBy = 'id'; // default to 'id' column if invalid column specified
         }
     
         if (!in_array($stockFilter, $validOperators)) {
             $stockFilter = '>'; // default to '>' operator if invalid operator specified
         }
     
         $products = Product::where(function ($query) use ($stockFilter) {
             if ($stockFilter == '>') {
                 $query->where('stock', '>', 0);
             } elseif ($stockFilter == '<') {
                 $query->where('stock', '<', 0);
             } elseif ($stockFilter == '=') {
                 $query->where('stock', '=', 0);
             }
         })
         ->orderBy($orderBy, $orderDirection)
         ->paginate($limit, '*', 'page', $page);
     
         return ['products' => $products];
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            // $file = $request->file('slip');
            // $imageName = time(). '.'. $file->getClientOriginalExtension();
            // $file->move('products', $imageName);
    
            // $product = Product::create(array_merge($request->all(), ['file' => $imageName]));
            $data = $request->all();
            $product = Product::create($data);
            $productInfo = [
                'productInfo' => $product,
                'status' => 200,
                'message' => 'Product created successfully'
            ];

            return response()->json($productInfo, 200); // 201 Created
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request'
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return $product;
        }
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
        $product = Product::find($id);
        // dd("dd", $product);
        // dd("dd", $request->all());
        if ($product) {
            $product->update($request->all());
            return [
                'productInfo' => $product,
                'status' => 200,
                'message' => 'Product updated successfully'
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
    public function destroy(Product $product)
    {
        $product->delete();
        return [
            'productInfo' => $product,
            'tatus' => 200,
            'essage' => 'Product deleted successfully'
        ];
    }
}
