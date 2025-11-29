<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_sheets', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->string('Question');
            $table->string('Type');
            $table->string('Option_1');
            $table->string('Option_2');
            $table->string('Option_3');
            $table->string('Option_4');
            $table->string('Right_Answers')->nullable(); // Make it nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excel_sheets'); // Drops the table if it exists
    }
}
