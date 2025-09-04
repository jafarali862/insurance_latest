<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireSubmission extends Model
{
    use HasFactory;
     protected $fillable = ['case_id','full_data'];
}
