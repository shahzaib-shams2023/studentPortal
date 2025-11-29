<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming conventions
    protected $table = 'curricula'; 

    // Define the fillable attributes
    protected $fillable = [
        'Curr_Name', 
        'Status',
    ];

    // Optionally, define the timestamps if you want to use them
    public $timestamps = true;

    // Define the primary key if it differs from the default (id)
    protected $primaryKey = 'id';

    // Define custom date formats if needed
    protected $dates = ['created_at', 'updated_at'];
}
