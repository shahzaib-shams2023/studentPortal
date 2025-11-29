<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebstudentofmonthmodelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webstudentofmonthmodels', function (Blueprint $table) {
            $table->id();
            $table->text("student_of_month_image");
            $table->string("month");
            $table->string("year");

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
        Schema::dropIfExists('webstudentofmonthmodels');
    }
}
