<?php

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Product = new Product();
        $Product->name          = "Personal Loan";
        $Product->product_code  = "PL";
        $Product->status = "1";
        $Product->created_by = '1';
        $Product->updated_by = '1';
        $Product->save();

        $Product = new Product();
        $Product->name = "Home Loan";
        $Product->product_code = "HL";
        $Product->status = "1";
        $Product->created_by = '1';
        $Product->updated_by = '1';
        $Product->save();

        $Product = new Product();
        $Product->name = "Auto Loan";
        $Product->product_code = "AL";
        $Product->status = "1";
        $Product->created_by = '1';
        $Product->updated_by = '1';
        $Product->save();

        $Product = new Product();
        $Product->name = "BSV";
        $Product->product_code = "BSV";
        $Product->status = "1";
        $Product->created_by = '1';
        $Product->updated_by = '1';
        $Product->save();

        $Product = new Product();
        $Product->name = "ITR";
        $Product->product_code = "ITR";
        $Product->status = "1";
        $Product->created_by = '1';
        $Product->updated_by = '1';
        $Product->save();
    }
}
