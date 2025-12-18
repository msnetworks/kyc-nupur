<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
// use App\Models\products;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        return view('backend.pages.products.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();
        return view('backend.pages.products.create', compact('roles'));
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
            'product_code' => 'required|max:50|unique:products,product_code',
        ]);
        // Create New products
        $products = new Product();
        $products->name         = $request->name;
        $products->product_code = $request->product_code;
        $products->created_by   = Auth::guard('admin')->user()->id;
        $products->updated_by   = Auth::guard('admin')->user()->id;
        $products->save();

        session()->flash('success', 'Product has been created !!');
        return redirect()->route('admin.products.index');
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
        $product  = Product::find($id);

        return view('backend.pages.products.edit', compact('product'));
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
        // Create New products
        $products = Product::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'product_code' => 'required|max:50|unique:products,product_code,' . $id,
        ]);

        $products->name = $request->name;
        $products->product_code = $request->product_code;
        $products->save();

        session()->flash('success', 'Product has been updated !!');
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
        $products = Product::find($id);
        if (!is_null($products)) {
            $products->delete();
        }

        session()->flash('success', 'Product has been deleted !!');
        return back();
    }
}
