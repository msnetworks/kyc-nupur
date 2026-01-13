<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
// use App\Models\Fitype;
use App\Models\FiType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FITypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fitype = FiType::all();
        return view('backend.pages.fitype.index', compact('fitype'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();
        return view('backend.pages.fitype.create', compact('roles'));
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
            'name' => 'required|max:50|unique:fi_types,name',
        ]);
        // Create New fitype
        $fitype = new FiType();
        $fitype->name         = $request->name;
        $fitype->created_by   = Auth::guard('admin')->user()->id;
        $fitype->updated_by   = Auth::guard('admin')->user()->id;
        // $fitype->name         = $request->name;
        $fitype->save();

        session()->flash('success', 'FI Type has been created !!');
        return redirect()->route('admin.fitypes.index');
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
        $fitype = FiType::find($id);

        return view('backend.pages.fitype.edit', compact('fitype'));
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
        // Create New FiType
        $fitype = FiType::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50|unique:fi_types,name,' . $id,

        ]);


        $fitype->name = $request->name;
        $fitype->save();

        session()->flash('success', 'FI Type has been updated !!');
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
        $fitype = FiType::find($id);
        if (!is_null($fitype)) {
            $fitype->delete();
        }

        session()->flash('success', 'Fi Type has been deleted !!');
        return back();
    }
}
