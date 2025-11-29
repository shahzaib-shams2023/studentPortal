<?php

namespace App\Exports;

use App\Models\feedback_form;
use Illuminate\Support\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;    


class Export_feedback implements FromCollection, WithHeadings
{
    public function collection()
    {
        $date = Carbon::now();
        $monthName = $date->format('F');
        $month = Carbon::now()->format('o');
        $dates = Carbon::now()->subMonth()->format('F');

               if(now()->format('d') >= 22)

		{
            return DB::table('feedback_forms')
                ->join('students', 'students.Std_id', 'feedback_forms.std_name_id')
                ->join('users', 'users.id', 'feedback_forms.faculty')
                ->select('batch', 'Std_Name', 'month', 'name', 'remark')
                ->where('month', '=', $monthName)
                ->where('year', '=', $month)
                ->whereNotNull('remark')
                ->get();
        } else {
            return DB::table('feedback_forms')
                ->join('students', 'students.Std_id', 'feedback_forms.std_name_id')
                ->join('users', 'users.id', 'feedback_forms.faculty')
                ->select('batch', 'Std_Name', 'month', 'name', 'remark')
                ->where('month', '=', $dates)
                ->where('year', '=', $month)
                ->whereNotNull('remark')
                ->get();
        }
    }

    public function headings(): array
    {
        return [
            'Batch',
            'Student Name',
            'Month',
            'Faculty',
            'Issue',
        ];
    }
}
