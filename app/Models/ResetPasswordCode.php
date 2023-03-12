<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPasswordCode extends Model
{
    use HasFactory;

    protected $table = "reset_password_codes";
    protected $guarded = [];
}