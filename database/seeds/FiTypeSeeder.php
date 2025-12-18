<?php

use App\Models\FiType;
use Illuminate\Database\Seeder;

class FiTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $FiType = new FiType();
        $FiType->name = "RV";
        $FiType->status = "1";
        $FiType->created_by = '1';
        $FiType->updated_by = '1';
        $FiType->save();

        $FiType = new FiType();
        $FiType->name = "BV";
        $FiType->status = "1";
        $FiType->created_by = '1';
        $FiType->updated_by = '1';
        $FiType->save();

        $FiType = new FiType();
        $FiType->name = "TV";
        $FiType->status = "1";
        $FiType->created_by = '1';
        $FiType->updated_by = '1';
        $FiType->save();

        $FiType = new FiType();
        $FiType->name = "PV";
        $FiType->status = "1";
        $FiType->created_by = '1';
        $FiType->updated_by = '1';
        $FiType->save();
    }
}
