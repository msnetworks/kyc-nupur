<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('admins', 'role')) {
            DB::statement('ALTER TABLE admins MODIFY role VARCHAR(255) NULL');

            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('admins', 'role')) {
            DB::statement('ALTER TABLE admins MODIFY role VARCHAR(50) NULL');
        }
    }
};
