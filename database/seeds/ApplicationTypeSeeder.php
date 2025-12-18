<?php

use App\Models\ApplicationType;
use Illuminate\Database\Seeder;

class ApplicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ApplicationType = new ApplicationType();
        $ApplicationType->name          = "Applicant";
        $ApplicationType->status        = "1";
        $ApplicationType->created_by    = '1';
        $ApplicationType->updated_by    = '1';
        $ApplicationType->save();

        $ApplicationType = new ApplicationType();
        $ApplicationType->name          = "Co-Applicant";
        $ApplicationType->status        = "1";
        $ApplicationType->created_by    = '1';
        $ApplicationType->updated_by    = '1';
        $ApplicationType->save();

        $ApplicationType = new ApplicationType();
        $ApplicationType->name          = "Guranter";
        $ApplicationType->status        = "1";
        $ApplicationType->created_by    = '1';
        $ApplicationType->updated_by    = '1';
        $ApplicationType->save();

        $ApplicationType = new ApplicationType();
        $ApplicationType->name          = "Seller";
        $ApplicationType->status        = "1";
        $ApplicationType->created_by    = '1';
        $ApplicationType->updated_by    = '1';
        $ApplicationType->save();
    }
}
