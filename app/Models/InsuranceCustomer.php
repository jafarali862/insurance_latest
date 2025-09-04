<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InsuranceCompany;

class InsuranceCustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'company_id',
        'name',
        'father_name',
        'phone',
        'emergency_contact_number',
        'email',
        'present_address',
        'permanent_address',
        'policy_no',
        'policy_start',
        'policy_end',
        'insurance_type',
        'intimation_report',
        'crime_number',
        'police_station',
        'status',
        'create_by',
        'update_by',
    ] ;

    public function company()
{
    return $this->belongsTo(InsuranceCompany::class, 'company_id');
}

}
