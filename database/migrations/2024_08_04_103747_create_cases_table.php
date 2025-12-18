<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('application_type');
            $table->integer('bank_id');
            $table->integer('product_id');
            $table->string('refrence_number');
            $table->enum('status', ['0', '1', '2', '3'])
                ->comment('0->inprogress,1->resolve,2->verified,3->rejected');
            $table->string('applicant_name');
            $table->float('amount');
            $table->string('vehicle');
            $table->string('co_applicant_name');
            $table->string('guarantee_name');
            $table->string('geo_limit');
            $table->string('tat_time');
            $table->text('remarks');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('cases');
    }
}
