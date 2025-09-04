<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdometerReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'check_in_km',
        'check_in_image',
        'check_in_time',
        'check_in_date',
        'check_in_latitude_and_longitude',
        'check_out_km',
        'check_out_image',
        'check_out_time',
        'check_out_date',
        'check_out_latitude_and_longitude',
        'date',
        'status',
    ];
}
