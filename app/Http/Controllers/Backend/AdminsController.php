<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\BranchCode;
use App\Exports\AdminsExport;
use App\User;
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
        $users = User::all();
        return view('backend.pages.admins.index', compact('admins', 'users'));
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
        $admin->is_blocked    = 0;
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
        $admin->role = implode(', ', $request->roles);

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

    /**
     * Assign users to an admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleBlock($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to block/unblock any admin !');
        }

        $admin = Admin::find($id);
        if (is_null($admin)) {
            abort(404, 'Admin not found !');
        }

        $admin->is_blocked = !$admin->is_blocked;
        $admin->save();

        $status = $admin->is_blocked ? 1 : 0;
        session()->flash('success', "Admin has been {$status} successfully!");
        return back();
    }

    public function bulkBlock(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to block/unblock any admin !');
        }

        $request->validate([
            'admin_ids' => 'required|array',
            'admin_ids.*' => 'integer',
            'action' => 'required|in:block,unblock',
        ]);

        $ids = $request->input('admin_ids', []);
        $action = $request->input('action');
        $isBlocked = $action === 'block' ? true : false;

        // Exclude super admin (id=1) from bulk operations
        $ids = array_filter($ids, fn($id) => $id != 1);

        Admin::whereIn('id', $ids)->update(['is_blocked' => $isBlocked]);

        $count = count($ids);
        session()->flash('success', "{$count} admin(s) have been {$action}ed successfully!");
        return back();
    }

    public function assignUsers(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to assign users to any admin !');
        }

        $admin = Admin::find($id);
        if (is_null($admin)) {
            abort(404, 'Admin not found !');
        }

        // Get the selected user IDs from the request
        $assignedUsers = $request->input('assigned_users', []);
        
        // Convert array to comma-separated string
        $assignedUsersStr = !empty($assignedUsers) ? implode(',', $assignedUsers) : '';
        
        // Update the admin's assigned_users field
        $admin->assigned_users = $assignedUsersStr;
        $admin->save();

        session()->flash('success', 'Users have been assigned to the admin successfully!');
        return back();
    }
}
