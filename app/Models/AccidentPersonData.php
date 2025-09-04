<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccidentPersonData extends Model
{
    use HasFactory;

    protected $fillable = [
        'assign_work_id', 'executive_id'];
}
