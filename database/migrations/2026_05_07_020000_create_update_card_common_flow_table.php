<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUpdateCardCommonFlowTable extends Migration
{
    public function up()
    {
        Schema::create('update_card_common_flow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_card_id');
            $table->unsignedBigInteger('status')->default(1);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_on')->nullable();
            $table->unsignedBigInteger('close_by')->nullable();
            $table->timestamp('closed_on')->nullable();
            $table->timestamps();

            $table->foreign('upload_card_id')->references('id')->on('upload_cards')->onDelete('cascade');
            $table->foreign('status')->references('id')->on('case_status');
            $table->foreign('verified_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('close_by')->references('id')->on('admins')->onDelete('set null');
            $table->unique('upload_card_id');
        });

        DB::table('upload_cards')->orderBy('id')->chunk(100, function ($uploadCards) {
            foreach ($uploadCards as $uploadCard) {
                $data = [
                    'upload_card_id' => $uploadCard->id,
                    'status' => $uploadCard->status ?: 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (in_array((int) $uploadCard->status, [4, 5])) {
                    $data['verified_by'] = $uploadCard->updated_by;
                    $data['verified_on'] = $uploadCard->updated_at ?: now();
                }

                if ((int) $uploadCard->status === 7) {
                    $data['close_by'] = $uploadCard->updated_by;
                    $data['closed_on'] = $uploadCard->updated_at ?: now();
                }

                DB::table('update_card_common_flow')->insert($data);
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('update_card_common_flow');
    }
}
