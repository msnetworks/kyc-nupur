<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadCardsTable extends Migration
{
    public function up()
    {
        Schema::create('upload_cards', function (Blueprint $table) {
            $table->id();
            $table->enum('use_type', ['web', 'mobile_app']);
            $table->string('employee_code', 50)->unique();
            $table->string('name', 100);
            $table->text('address');
            $table->string('mobile_no', 15);
            $table->string('aadhaar_card', 20);
            $table->string('photo')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('upload_cards');
    }
}
