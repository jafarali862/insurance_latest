<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        "case_id",
        "company_id",
        "customer_id",
        "executive_driver",
        "driver_reassign_status",
        "executive_garage",
        "garage_reassign_status",
        "executive_spot",
        "spot_reassign_status",
        "executive_meeting",
        "owner_reassign_status",
        "executive_accident_person",
        "accident_person_reassign_status",
        "date",
        "type",
        "other",
        "status",
        "case_status",
        "create_by",
        "update_by",
    ] ;
}
