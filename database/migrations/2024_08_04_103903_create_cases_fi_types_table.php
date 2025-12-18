<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesFiTypesTable extends Migration
{
    public function up()
    {
        Schema::create('cases_fi_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->integer('fi_type_id');
            $table->string('mobile');
            $table->text('address');
            $table->integer('pincode');
            $table->text('land_mark');
            $table->integer('user_id');
            $table->dateTime('scheduled_visit_date');
            $table->timestamps();


            // Define the foreign key constraint
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::table('cases_fi_types', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['case_id']);
        });

        // Drop the table
        Schema::dropIfExists('cases_fi_types');
    }
}
