<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use App\Models\ExcelSheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function model(array $row)
    {
        // Log the entire row to see its contents
        Log::info($row); // This will log the row content to your Laravel log file

        // Check if exam_id exists and handle the undefined key situation
        $examId = $row['exam_id'] =! null; // Using null coalescing to prevent undefined index error

        // Check if 'exam_id' exists in the row and log it if it's not found
        if (!array_key_exists('exam_id', $row)) {
            Log::warning('exam_id not found in row', $row);
        }

        return new ExcelSheet([
            'exam_id' => $examId, // Corrected variable reference
            'Question' => $row['question'] ,
            'Type' => $row['type'] ,
            'Option_1' => $row['option_1'] ,
            'Option_2' => $row['option_2'] ,
            'Option_3' => $row['option_3'] ,
            'Option_4' => $row['option_4'] ,
            'Right_Answers' => $row['right_answers']
        ]);
    }
}
