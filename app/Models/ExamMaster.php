<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMaster extends Model
{
    use HasFactory;

    protected $table = 'exammasters';
    protected $fillable = ['Curr_ID', 'Sem_ID', 'exam_info'];
}
