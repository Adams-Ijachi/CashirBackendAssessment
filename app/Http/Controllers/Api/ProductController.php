<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests\{
    ProductCreateRequest,
    ProductUpdateRequest,
};

class ProductController extends Controller
{
    public function index()
    {
        try {
            //code...
            $products = Product::paginate(10);
            return ProductResource::collection($products);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
   
    }

    public function store(ProductCreateRequest $request)
    {


        try {
            $product = Product::create($request->validated());

            return (new ProductResource($product))->additional(
                [
                    'message' => 'Product created successfully'
                ],
            )->response()->setStatusCode(201);

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function show (Product $product) 
    {
        try {

            return (new ProductResource($product))->additional(
                [
                    'message' => 'Product found'
                ],
            );

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ProductCreateRequest $request, Product $product)
    {
        try {
            //code...
            $product->update($request->validated());
            return (new ProductResource($product))->additional(
                [
                    'message' => 'Product updated successfully'
                ],
            );

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    
    public function destroy(Product $product)
    {
        try {
            //code...
            $product->delete();
            return response()->noContent();

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }





}
