<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\BranchCode;
use Illuminate\Support\Facades\Auth;
class BranchCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branchcode = BranchCode::with('bank')->get();
        return view('backend.pages.branchcode.index', compact('branchcode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::guard('admin')->user()->id != 1){
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $banks = Bank::whereIn('id', $assignBank)->get();
        }else{
            $banks = Bank::all();
        }
        return view('backend.pages.branchcode.create', compact( 'banks'));
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
            'bank_id' => 'required|numeric',
            'branch_name' => 'required|max:100',
            'branch_code' => 'required|max:50|unique:branch_code,branch_code',
        ]);
        
        // Create New banks
        $branch = new BranchCode();
        $branch->bank_id  = $request->bank_id;
        $branch->branch_name  = $request->branch_name;
        $branch->branch_code  = $request->branch_code;
        $branch->area  = $request->area;
        $branch->save();
        $branch = $branch->id;
        
        session()->flash('success', 'Branch has been created !!');
        return redirect()->route('admin.branchcodes.index');
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
        
        if(Auth::guard('admin')->user()->id != 1){
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $banks = Bank::whereIn('id', $assignBank)->get();
        }else{
            $banks = Bank::all();
        }
        $branch = BranchCode::find($id);
        // Load all available products
        return view('backend.pages.branchcode.edit', compact('branch','banks'));
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
        // Validate the input data
        $request->validate([
            'bank_id' => 'required|numeric',
            'branch_name' => 'required|max:100',
            'branch_code' => 'required|max:50|unique:branch_code,branch_code,' . $id,  
        ]);
    
        // Find the branch by id
        $branch = BranchCode::find($id);
        
        // If branch not found, return an error message
        if (!$branch) {
            session()->flash('error', 'Branch not found!');
            return back();
        }
    
        // Update the branch data
        $branch->bank_id = $request->bank_id;
        $branch->branch_name = $request->branch_name;
        $branch->branch_code = $request->branch_code;
        $branch->area = $request->area;
        
        // Save the updated data
        $branch->save();
    
        // Flash success message and return back
        session()->flash('success', 'Branch has been updated!');
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
        $BranchCode = BranchCode::find($id);
        if (!is_null($BranchCode)) {
            $BranchCode->delete();
        }

        session()->flash('success', 'Branch Code has been deleted !!');
        return back();
    }
    public function getBranches($id)
    {
        $branches = BranchCode::where('bank_id', $id)->get();
        return response()->json($branches);     
    }
    
}
