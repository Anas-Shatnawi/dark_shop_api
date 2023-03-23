<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'orders_details';

    protected $guarded = [];

    protected $with = ['product'];
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

}
