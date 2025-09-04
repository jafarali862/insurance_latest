<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
     protected $fillable = [
        'company_id',
        'table_name',
        'template_name',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array', // Automatically cast JSON to array
    ];

    public function company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'company_id');
    }
}


