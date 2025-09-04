<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignWorkData extends Model
{
    use HasFactory;
    protected $fillable = [
        "case_id",
        "garage_reassign_status",
        "garage_re_assign_count",
        "driver_reassign_status",
        "driver_re_assign_count",
        "spot_reassign_status",
        "spot_re_assign_count",
        "owner_reassign_status",
        "owner_re_assign_count",
        "accident_person_reassign_status",
        "accident_person_re_assign_count",
    ];

    public function garageData()
    {
        return $this->hasMany(GarageData::class, 'assign_work_id');
    }

    public function driverData()
    {
        return $this->hasMany(DriverData::class, 'assign_work_id');
    }

    public function spotData()
    {
        return $this->hasMany(SpotData::class, 'assign_work_id');
    }

    public function ownerData()
    {
        return $this->hasMany(OwnerData::class, 'assign_work_id');
    }

    public function accidentPersonData()
    {
        return $this->hasMany(AccidentPersonData::class, 'assign_work_id');
    }
}
