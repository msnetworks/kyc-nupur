<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\BranchCode;
use App\Exports\AdminsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminsController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $admins = Admin::all();

        return view('backend.pages.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $roles  = Role::all();
        $banks  = Bank::all();
        return view('backend.pages.admins.create', compact('roles','banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'mobile' => 'required|min:10|max:10',
            'email' => 'required|max:100|email|unique:admins',
            'username' => 'required|max:100|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);
        $bank = 0;
        if($request->bank){
            $bank = implode(',',$request->bank);
        }
        // Create New Admin
        $admin              = new Admin();
        $admin->name        = $request->name;
        $admin->username    = $request->username;
        $admin->role        = implode(', ', $request->roles);
        if ($request->roles) {
            if(in_array('Bank', $request->roles)){
                $admin->default_agent_assign = $request->default_agent_assign;
            }
        }
        $admin->email       = $request->email;
        $admin->mobile      = $request->mobile;
        $admin->banks_assign = $bank;
        $admin->branch_assign = $request->branch_assign ?? null;
        $admin->parent_id   = Auth::guard('admin')->user()->id;
        $admin->password    = Hash::make($request->password);
        $admin->view_password = $request->password;
        $admin->save();

        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been created !!');
        return redirect()->route('admin.admins.index');
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        $admin = Admin::find($id);
        $roles  = Role::all();
        $banks  = Bank::all();
        $branchcode  = BranchCode::all();
        return view('backend.pages.admins.edit', compact('admin', 'roles', 'banks', 'branchcode'));
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        // Create New Admin
        $admin = Admin::find($id);

        // Validation Data
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $bank = 0;
        if($request->banks_assign){
            $bank = implode(',',$request->banks_assign);
        }
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->mobile = $request->mobile;
        

        $admin->banks_assign = $bank;
        $admin->branch_assign = $request->branch_assign ?? null;
        $admin->username = $request->username;
        if ($request->password) {
            if(Auth::guard('admin')->user()->role == 'superadmin'){
                $admin->view_password = $request->password;
                $admin->password = Hash::make($request->password);
            }
        }
        if ($request->roles) {
            if(in_array('Bank', $request->roles)){
                $admin->default_agent_assign = $request->default_agent_assign;
            }
        }
        $admin->roles()->detach();
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'Admin has been updated !!');
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
        if (is_null($this->user) || !$this->user->can('admin.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
        }

        $admin = Admin::find($id);
        if (!is_null($admin)) {
            $admin->delete();
        }

        session()->flash('success', 'Admin has been deleted !!');
        return back();
    }

    /**
     * Export admins to Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            abort(403, 'Sorry !! You are Unauthorized to export admins !');
        }

        return Excel::download(new AdminsExport, 'admins_list.xlsx');
    }
}
