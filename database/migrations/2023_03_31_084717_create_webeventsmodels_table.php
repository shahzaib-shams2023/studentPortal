<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebeventsmodelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webeventsmodels', function (Blueprint $table) {
            $table->id();
            $table->text("image");
            $table->text("title");
            $table->text("timing");
            $table->text("date");
            $table->text("month");
            $table->text("year");
            $table->text("description");
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
        Schema::dropIfExists('webeventsmodels');
    }
}
