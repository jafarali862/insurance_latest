<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCase extends Model
{
    use HasFactory;

    protected $fillable = ["case_id","company_id","customer_id","insurance_type","case_details","status","assigned_status","case_status","create_by","update_by"] ;
}
