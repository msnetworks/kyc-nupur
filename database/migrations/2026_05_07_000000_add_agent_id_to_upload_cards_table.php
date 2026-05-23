<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentIdToUploadCardsTable extends Migration
{
    public function up()
    {
        Schema::table('upload_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('updated_by');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('upload_cards', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
}
