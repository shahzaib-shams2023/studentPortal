<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('curr_id')->nullable()->default(1); // Example default value
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('exam_id');
            $table->date('exam_date')->nullable(); // Corrected from string to date
            $table->time('starttime')->nullable(); // Add starttime column
            $table->time('endtime')->nullable();   // Add endtime column
            $table->unsignedBigInteger('Std_id'); // Foreign key for the student
            $table->timestamps();

            // Foreign keys and constraints
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('exam_id')->references('ExamType_ID')->on('examsubjectmasters')->onDelete('cascade'); // Adjusted table name
            $table->foreign('Std_id')->references('Std_id')->on('students')->onDelete('cascade'); // Foreign key for students

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_assignments', function (Blueprint $table) {
            $table->dropColumn('starttime');
            $table->dropColumn('endtime');
        });
    }
}
