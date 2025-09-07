<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
     protected $fillable = [
        'question',
        'input_type',
        'data_category',
        'column_name',
        'unique_key',
         'file_type',
    ];

    public function templates()
    {
    return $this->belongsToMany(Template::class, 'question_template');
    }


}
