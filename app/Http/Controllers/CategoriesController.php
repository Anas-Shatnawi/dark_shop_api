<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function getCategories(){
        $categories = Category::all();

        return response()->json([
            'status' => 200,
            'message' => 'all categories',
            'data' => $categories
        ]);
    }
}
