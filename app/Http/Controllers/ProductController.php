<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $products = Product::orderBy('id', 'DESC')->limit(100)->get();

        return response()->json([
            'status' => 200,
            'message' => '100 products',
            'data' => $products,
        ]);
    }

    public function getProductDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $product = Product::find($request->productId);
        
        return response()->json([
            'status' => 200,
            'message' => 'product details',
            'data' => $product,
        ]);
    }

    public function getUserProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $products = Product::where('user_id', $request->userId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'user products',
            'data' => $products,
        ]);
    }

    public function getProductsByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $products = Product::where('category_id', $request->categoryId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'products by category',
            'data' => $products,
        ]);
    }
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'required|exists:users,id',
            'userId' => 'required|exists:users,id',
            'name' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required',
            'items.*.image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }
        //insert product data
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->categoryId,
            'user_id' => $request->userId,
            'description' => $request->description,
        ]);

        // images
        $images = [];
        $folderName = 'product' . '-' . $product->id;

        foreach ($request->file('images') as $image) {
            // insert images to the storage
            $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = url('/') . '/' . $image->storeAs('public/productImages/' . $folderName, $fileName);

            $images[] = [
                'product_id' => $product->id,
                'image' => $path,
            ];
        }

        // insert images to the data base
        $images = ProductImage::insert($images);

        return response()->json([
            'status' => 200,
            'message' => 'product created successfully',
            'data' => $product,
        ]);
    }

    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric',
            'categoryId' => 'required|exists:users,id',
            'userId' => 'required|exists:users,id',
            'name' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required',
            'items.*.image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        // update product data
        $product = Product::where('id', $request->productId)->first();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->categoryId;
        $product->user_id = $request->userId;
        $product->description = $request->description;
        $product->save();

        // check if theres an image sent
        if (count($request->file('images')) > 0) {

            $images = [];
            $folderName = 'product' . '-' . $product->id;

            // delete the old images
            ProductImage::where('product_id', $product->id)->delete();
            $deleteFolder = storage::deleteDirectory('public/productImages/' . $folderName);

            // insert the new images
            foreach ($request->file('images') as $image) {
                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = url('/') . '/' . $image->storeAs('public\productImages' . '\\' . $folderName, $fileName);

                $images[] = [
                    'product_id' => $product->id,
                    'image' => $path,
                ];
            }
            $images = ProductImage::insert($images);
        }

        return response()->json([
            'status' => 200,
            'message' => 'product updated successfully',
            'data' => $product,
        ]);
    }

    public function deleteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|exists:products,id',
            'userId' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $product = Product::find($request->productId);

        if ($product->user_id !== $request->userId) {
            return response()->json([
                'status' => 400,
                'error' => 'the user dosent have permission to delete this product',
            ]);
        }

        $product->delete();

        $images = ProductImage::where('product_id', $product->id)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'product deleted successfully',
        ]);
    }

    public function getSortFilter(Request $request){
        $validator = Validator::make($request->all(),[
            'sort' => 'numeric',
            'minPrice' => 'numeric',
            'maxPrice' => 'numeric',
            'categoryId' => 'numeric|exists:categories,id',
            'userId' => 'numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors() 
            ]);
        }

        $products = Product::all();
        // sorts
        if ($request->sort == 1) {
            $products = Product::orderBy('price','DESC');
        }
        if ($request->sort == 2) {
            $products = Product::orderBy('price','ASC');
        }
        if ($request->sort == 3) {
            $products = Product::orderBy('id','DESC');
        }

        // filters
        // pricing filters
        if ($request->minPrice && $request->maxPrice) {
            $products = $products->whereBetween('price',[$request->minPrice,$request->maxPrice]);
        }elseif ($request->minPrice) {
            $products = $products->where('price','>=',$request->minPrice);
        }elseif ($request->maxPrice) {
            $products = $products->where('price','<=',$request->maxPrice);
        }
        
        //category filter
        if ($request->categoryId) {
            $products = $products->where('category_id',$request->category)->get();
        }

        //user filter
        if ($request->categoryId) {
            $products = $products->where('user_id',$request->category)->get();
        }

        $products = $products->get();
        
        return response()->json([
            'status' => 200,
            'message' => 'filter and sort api successfully called',
            'data' => $products,
        ]);
    }
}
