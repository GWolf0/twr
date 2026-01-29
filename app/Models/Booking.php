<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [ 
        "user_id", "vehicle_id", "start_date", "end_date", "status", "payment_status", "payment_method", "total_amount"
    ];
}
