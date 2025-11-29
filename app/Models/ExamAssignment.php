<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAssignment extends Model
{
    protected $fillable = [
        'batch_id',
        'curr_id',
        'semester_id',
        'exam_id',
        'exam_date',
        'Start_Time',
        'End_Time',
        'Std_id',
        'created_at',
        'updated_at',
    ];
}
