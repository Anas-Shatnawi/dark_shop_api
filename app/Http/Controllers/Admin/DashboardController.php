<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index(){
        $usersCount = count(User::all());
        $ordersCount = count(Order::all());
        $productsCount = count(Product::all());
        $categoriesCount = count(Category::all());
        $stores = User::whereRoleIs('store')->get();
        $storesCount = count($stores);
        $commentsCount = count(Comment::all());

        return view('admin.dashboard',compact('usersCount','ordersCount','productsCount','commentsCount','storesCount','categoriesCount'));
    }
}
