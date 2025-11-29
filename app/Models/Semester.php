<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'semesters'; // Adjust if your table name is different

    // If you have timestamps in your table
    public $timestamps = true;

    // Specify the fillable fields
    protected $fillable = [
        'Sem_Name', // Add other fields as needed
        // e.g., 'description', 'start_date', 'end_date', etc.
    ];

    // Define any relationships, if necessary
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
