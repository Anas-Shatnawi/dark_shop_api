<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = [];

    protected $with = ['user','OrderDetails'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function OrderDetails(){
        return $this->hasMany(OrderDetails::class);
    }
}
