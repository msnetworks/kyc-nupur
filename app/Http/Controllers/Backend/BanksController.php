<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Bank;
use App\Models\Product;
use App\Models\BankProductMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::all();
        return view('backend.pages.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products  = Product::all();
        return view('backend.pages.banks.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'branch_code' => 'required|max:50|unique:banks,branch_code',
        ]);
        // Create New banks
        $banks = new Bank();
        $banks->name         = $request->name;
        $banks->branch_code  = $request->branch_code;
        $banks->created_by   = Auth::guard('admin')->user()->id;
        $banks->updated_by   = Auth::guard('admin')->user()->id;
        $banks->save();
        $bank_id = $banks->id;
        foreach ($request->bank_products as $product_id) {

            $mapping = new BankProductMapping;
            $mapping->bank_id = $bank_id;
            $mapping->product_id = $product_id;
            $mapping->save();
        }
        session()->flash('success', 'Bank has been created !!');
        return redirect()->route('admin.banks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Find the existing bank by ID and load associated products
        $bank = Bank::with('products')->findOrFail($id);

        // Load all available products
        $products  = Product::all();

        return view('backend.pages.banks.edit', compact('bank', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Create New banks
        $request->validate([
            'name' => 'required|max:50',
            'branch_code' => 'required|max:50|unique:banks,branch_code,' . $id,
        ]);
        BankProductMapping::where('bank_id', $id)->delete();

        foreach ($request->bank_products as $product_id) {

            $mapping = new BankProductMapping;
            $mapping->bank_id = $id;
            $mapping->product_id = $product_id;
            $mapping->save();
        }

        session()->flash('success', 'Bank has been updated !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banks = Bank::find($id);
        if (!is_null($banks)) {
            $banks->delete();
        }

        session()->flash('success', 'Bank has been deleted !!');
        return back();
    }
}
