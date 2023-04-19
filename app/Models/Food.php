<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = ['registration', "first_snack_am",
    "first_snack_pm",
    "second_snack_am",
    "second_snack_pm",
    "first_lunch",
    "second_lunch",];
    
}
