<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUploadCardsStatusToMasterStatus extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('upload_cards') || !Schema::hasTable('case_status')) {
            return;
        }

        foreach ($this->uploadCardStatuses() as $id => $name) {
            if (!DB::table('case_status')->where('id', $id)->exists()) {
                DB::table('case_status')->insert([
                    'id' => $id,
                    'name' => $name,
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::statement('ALTER TABLE upload_cards MODIFY status BIGINT UNSIGNED NOT NULL DEFAULT 1');
        DB::table('upload_cards')->where('status', 0)->update(['status' => 7]);
        DB::table('upload_cards')->where('status', 2)->update(['status' => 4]);
        DB::table('upload_cards')->where('status', 3)->update(['status' => 5]);
        DB::table('upload_cards')->whereNull('status')->update(['status' => 1]);
        DB::statement('ALTER TABLE upload_cards ADD CONSTRAINT upload_cards_status_foreign FOREIGN KEY (status) REFERENCES case_status(id)');
    }

    public function down()
    {
        if (!Schema::hasTable('upload_cards')) {
            return;
        }

        DB::statement('ALTER TABLE upload_cards DROP FOREIGN KEY upload_cards_status_foreign');
        DB::table('upload_cards')->whereNotIn('status', [0, 1])->update(['status' => 1]);
        DB::statement("ALTER TABLE upload_cards MODIFY status ENUM('0','1') NOT NULL DEFAULT '1'");
    }

    private function uploadCardStatuses()
    {
        return [
            1 => 'Inprogress',
            4 => 'Positive Verified',
            5 => 'Negative Verified',
            7 => 'Closed',
        ];
    }
}
