<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InsuranceCustomer;

class InsuranceCompany extends Model
{
    use HasFactory;

    protected $fillable = ["company_id","name","contact_person","email","phone","address",'template',"status","selected_tabs","questionnaires","create_by","update_by"];


    protected $casts = ['selected_tabs' => 'array','questionnaires' => 'array'];

    public function customers()
    {
        return $this->hasMany(InsuranceCustomer::class, 'company_id');
    }

}
