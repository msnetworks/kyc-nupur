<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\Bank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminsExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $admins = Admin::with(['defaultAgent', 'parentAdmin'])->where('id', '!=', 1)->get();
        
        return $admins->map(function ($admin) {
            // Get bank names
            $bankNames = '';
            if ($admin->banks_assign) {
                $bankIds = explode(',', $admin->banks_assign);
                $banks = DB::table('banks')->whereIn('id', $bankIds)->pluck('name')->toArray();
                $bankNames = implode(', ', $banks);
            }
            
            // Get role names
            $roleNames = $admin->roles->pluck('name')->implode(', ');
            
            // Get branch code names
            $branchNames = '';
            if ($admin->branch_assign) {
                $branchIds = explode(',', $admin->branch_assign);
                $branches = DB::table('branch_code')->whereIn('id', $branchIds)->pluck('branch_name')->toArray();
                $branchNames = implode(', ', $branches);
            }
            
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
                'mobile' => $admin->mobile,
                'password' => $admin->view_password ?? '',
                'role' => $admin->role ?? '',
                'roles' => $roleNames,
                'banks_assign' => $bankNames,
                'branch_assign' => $branchNames,
                'default_agent_name' => $admin->defaultAgent ? $admin->defaultAgent->name : '',
                'parent_admin_name' => $admin->parentAdmin ? $admin->parentAdmin->name : '',
                'created_at' => $admin->created_at ? $admin->created_at->format('Y-m-d H:i:s') : '',
                'updated_at' => $admin->updated_at ? $admin->updated_at->format('Y-m-d H:i:s') : '',
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Username',
            'Email',
            'Mobile',
            'Password',
            'Role',
            'Roles (Spatie)',
            'Banks Assigned',
            'Branch Assigned',
            'Default Agent Name',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
                  'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4472C4']]],
        ];
    }
}
