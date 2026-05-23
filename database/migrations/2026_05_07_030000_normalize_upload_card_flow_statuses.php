<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NormalizeUploadCardFlowStatuses extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('case_status')) {
            return;
        }

        foreach ($this->statuses() as $id => $name) {
            if (DB::table('case_status')->where('id', $id)->exists()) {
                DB::table('case_status')->where('id', $id)->update([
                    'name' => $name,
                    'status' => '1',
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('case_status')->insert([
                    'id' => $id,
                    'name' => $name,
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('upload_cards')) {
            DB::table('upload_cards')->where('status', 2)->update(['status' => 4]);
            DB::table('upload_cards')->where('status', 3)->update(['status' => 5]);
        }

        if (Schema::hasTable('update_card_common_flow')) {
            DB::table('update_card_common_flow')->where('status', 2)->update(['status' => 4]);
            DB::table('update_card_common_flow')->where('status', 3)->update(['status' => 5]);
        }
    }

    public function down()
    {
        if (Schema::hasTable('case_status')) {
            foreach ([2 => 'Positive Verified', 3 => 'Negative Verified'] as $id => $name) {
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
        }

        if (Schema::hasTable('update_card_common_flow')) {
            DB::table('update_card_common_flow')->where('status', 4)->update(['status' => 2]);
            DB::table('update_card_common_flow')->where('status', 5)->update(['status' => 3]);
        }

        if (Schema::hasTable('upload_cards')) {
            DB::table('upload_cards')->where('status', 4)->update(['status' => 2]);
            DB::table('upload_cards')->where('status', 5)->update(['status' => 3]);
        }
    }

    private function statuses()
    {
        return [
            1 => 'Inprogress',
            4 => 'Positive Verified',
            5 => 'Negative Verified',
            7 => 'Closed',
        ];
    }
}
