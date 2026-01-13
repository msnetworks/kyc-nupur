<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CaseStatus;
use App\Models\User;
use App\Models\Bank;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $session_id  = Auth::guard('admin')->user()->id;
        if (Auth::guard('admin')->check() && 
            in_array('superadmin', explode(',', Auth::guard('admin')->user()->role))) {
            $users = User::get();
        } else {
            $users = User::whereRaw("FIND_IN_SET(?, admin_access)", [$session_id])->get();
        }
        return view('backend.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);

        $banks = Bank::whereIn('id', $assignBank)->get();

        $admins = Admin::select('id', 'name', 'banks_assign')->get();

        $getAdmin = [];

        $bankMapping = $banks->keyBy('id')->map(function ($bank) {
            return $bank->name;
        });

        foreach ($assignBank as $bankid) {
            if ($bankMapping->has($bankid)) {
                foreach ($admins as $data) {
                    $bankArray = explode(',', $data->banks_assign);
                    if (in_array($bankid, $bankArray)) {
                        $getAdmin[] = [
                            'id' => $data->id,
                            'name' => $data->name,
                            'bank_id' => $bankid,
                            'bank_name' => $bankMapping[$bankid],
                        ];
                    }
                }
            }
        }
        $mergedAdmins = [];

        foreach ($getAdmin as $admin) {
            $adminId = $admin['id'];
            if (isset($mergedAdmins[$adminId])) {
                $mergedAdmins[$adminId]['bank_name'] .= ', ' . $admin['bank_name'];
            } else {
                $mergedAdmins[$adminId] = [
                    'id' => $admin['id'],
                    'name' => $admin['name'],
                    'bank_id' => $admin['bank_id'],
                    'bank_name' => $admin['bank_name']
                ];
            }
        }

        // Reset keys to create a clean indexed array
        $accessAdmin = array_values($mergedAdmins);

        return view('backend.pages.users.create', compact('roles', 'banks', 'admins', 'accessAdmin'));
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
            'name'      => 'required|max:50',
            'username'  => 'required|max:100|unique:users',
            'email'     => 'required|max:100',
            'password'  => 'required|min:6|confirmed',
        ]);
        $assignedAdmins = 0;
        if ($request->admin_access) {
            $assignedAdmins = implode(',', $request->admin_access);
        }
        // Create New User
        $user = new User();
        $user->username         = $request->username;
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->admin_access       = $assignedAdmins;
        $user->admin_id     = Auth::guard('admin')->user()->id;
        $user->view_password = $request->password;
        $user->password     = Hash::make($request->password);
        $user->save();

        // if ($request->roles) {
        //     $user->assignRole($request->roles);
        // }

        session()->flash('success', 'User has been created !!');
        return redirect()->route('admin.users.index');
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
        $user = User::find($id);
        $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);

        $banks = Bank::whereIn('id', $assignBank)->get();

        $admins = Admin::select('id', 'name', 'banks_assign')->get();

        $getAdmin = [];

        $bankMapping = $banks->keyBy('id')->map(function ($bank) {
            return $bank->name;
        });

        foreach ($assignBank as $bankid) {
            if ($bankMapping->has($bankid)) {
                foreach ($admins as $data) {
                    $bankArray = explode(',', $data->banks_assign);
                    if (in_array($bankid, $bankArray)) {
                        $getAdmin[] = [
                            'id' => $data->id,
                            'name' => $data->name,
                            'bank_id' => $bankid,
                            'bank_name' => $bankMapping[$bankid],
                        ];
                    }
                }
            }
        }

        $mergedAdmins = [];

        foreach ($getAdmin as $admin) {
            $adminId = $admin['id'];
            if (isset($mergedAdmins[$adminId])) {
                $mergedAdmins[$adminId]['bank_name'] .= ', ' . $admin['bank_name'];
            } else {
                $mergedAdmins[$adminId] = [
                    'id' => $admin['id'],
                    'name' => $admin['name'],
                    'bank_id' => $admin['bank_id'],
                    'bank_name' => $admin['bank_name']
                ];
            }
        }

        $accessAdmin = array_values($mergedAdmins);

        return view('backend.pages.users.edit', compact('user', 'banks', 'admins', 'accessAdmin'));
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
        // Create New User
        $user = User::find($id);

        // Validation Data
        $request->validate([
            'name'      => 'required|max:50',
            'username'  => 'required|max:100|unique:users,username,' . $id,
            'email'     => 'required|max:100',
            'password'  => 'required|min:6|confirmed',
        ]);

        $assignedAdmins = 0;
        if ($request->admin_access) {
            $assignedAdmins = implode(',', $request->admin_access);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->admin_access       = $assignedAdmins;

        if ($request->password) {
            $user->view_password = $request->password;
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        session()->flash('success', 'User has been updated !!');
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
        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        session()->flash('success', 'User has been deleted !!');
        return back();
    }
    // public function getAgent($bankId = null)
    // {
    //     $user = Auth::guard('admin')->user();
    //     if($user->role == 'superadmin'){
    //     $users = User::get();
    //     }else{
    //     $users = User::whereRaw("FIND_IN_SET(?, admin_access)", [$user->id])->get();
    //     }
    //     if ($bankId !== null) {
    //         return response()->json(['users' => $users]);
    //     } else {
    //         return response()->json(['error' => 'Bank ID not provided.'], 400);
    //     }
    // }
    
    public function getAgent($bankId = null)
    {
        $user = Auth::guard('admin')->user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
    
        if ($user->role == 'superadmin') {
            $users = User::get();
        } else {
            $users = User::whereRaw("FIND_IN_SET(?, admin_access)", [$user->id])->get();
        }
    
        if ($bankId !== null) {
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }

    public function getCaseStatus($type, $parent_id = null)
    {
        if (is_null($parent_id)) {
            $caseSubStatus = CaseStatus::where('type', $type)->orderBy('name', "ASC")->get();
        } else {
            $caseSubStatus = CaseStatus::where('parent_id', $parent_id)->where('type', $type)->orderBy('name', "ASC")->get();
        }


        if ($caseSubStatus !== null) {
            return response()->json(['caseSubStatus' => $caseSubStatus]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
}
