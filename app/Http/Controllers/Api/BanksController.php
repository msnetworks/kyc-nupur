<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Bank;
use App\Models\FiType;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBank()
    {
        $AvailbleBank = Bank::select('id', 'name', 'branch_code', 'status')
            ->get()->toArray();

        if ($AvailbleBank !== null) {
            return response()->json(['AvailbleBank' => $AvailbleBank]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
    public function getFiType()
    {
        $AvailbleFiTypes = FiType::select('id', 'name', 'status')
            ->get()->toArray();

        if ($AvailbleFiTypes !== null) {
            return response()->json(['AvailbleFiTypes' => $AvailbleFiTypes]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
    public function getProduct($bankId = null)
    {
        $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
            ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
            ->where('bpm.bank_id', $bankId)
            ->where('products.status', '1')
            ->get()->toArray();


        if ($bankId !== null) {
            return response()->json(['AvailbleProduct' => $AvailbleProduct]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
}
