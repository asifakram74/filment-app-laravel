<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ['stock' => Stock::paginate(10)];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            dd("aa",time(). $data[0]['productID']);
            
            foreach ($data as $item) {
                $dataModel = new Stock();
                $dataModel->title = $item['title'];
                $dataModel->fname = $item['fname'];
                $dataModel->lname = $item['lname'];
                $dataModel->passport = $item['passport'];
                $dataModel->serial = $item['serial'];
                $dataModel->productID = $item['productID'];
                $dataModel->UserID = $item['UserID'];
                $dataModel->batchID = time(). '.'.$item['productID'];
                $dataModel->save();
            }

            
            // $stock = Stock::create($data);
            $stockinfo = [
                'stock' => $data,
                'status' => 200,
                'message' => 'stock created successfully'
            ];

            return response()->json($stockinfo, 200); // 201 Created
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            dd("Data1", $request->all());
            return response()->json([
                'message' => 'An error occurred while processing your request'
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function productStock(Request $request, $product_id)
    {
        $stock = Stock::where('productID', $product_id)->get();
        return  [
            'userInfo' => $stock,
            'status' => 200,
            'message' => 'User Deleted successfully'
        ];
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function userStock(Request $request, $user_id)
    {
        $stock = Stock::where('UserID', $user_id)->get();
        return  [
            'userInfo' => $stock,
            'status' => 200,
            'message' => 'User Deleted successfully'
        ];
    }


    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
