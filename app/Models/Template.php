<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
     protected $fillable = [
        'company_id',
        'template_id',

    ];


    public function company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'company_id');
    }

    // Template.php
   public function questions()
{
    return $this->belongsToMany(Question::class, 'question_template');
}


    

}


