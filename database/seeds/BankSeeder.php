<?php

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Bank = new Bank();
        $Bank->name         = "SBI Bank";
        $Bank->branch_code  = "sbi noida";
        $Bank->status       = "1";
        $Bank->created_by   = '1';
        $Bank->updated_by   = '1';
        $Bank->save();

        $Bank = new Bank();
        $Bank->name         = "KOTAK Bank";
        $Bank->name         = "kotak noida";
        $Bank->status       = "1";
        $Bank->created_by   = '1';
        $Bank->updated_by   = '1';
        $Bank->save();

        $Bank = new Bank();
        $Bank->name         = "HDFC Bank";
        $Bank->name         = "hdfc noida";
        $Bank->status       = "1";
        $Bank->created_by   = '1';
        $Bank->updated_by   = '1';
        $Bank->save();

        $Bank = new Bank();
        $Bank->name         = "ICICI Bank";
        $Bank->name         = "icici noida";
        $Bank->status       = "1";
        $Bank->created_by   = '1';
        $Bank->updated_by   = '1';
        $Bank->save();
    }
}
