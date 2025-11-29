<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches'; // Ensure this matches your database table name

    protected $fillable = [
        'Batch_Name', // Add other fields as necessary
    ];

    public $timestamps = true; // If you want to use created_at and updated_at
}
