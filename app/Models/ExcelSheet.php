<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelSheet extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id','Question', 'Type', 'Option_1', 'Option_2', 'Option_3', 'Option_4','Right_Answers']; // Add other fields as needed

    protected $table = 'excel_sheets'; // Change this to your desired table name
}
