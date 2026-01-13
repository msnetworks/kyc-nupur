<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Exports\CasesExport;
use App\Exports\CasesCountExport;
use App\Exports\BillingExport; // Add this at the top
use App\Models\Admin;
use App\Models\CaseStatus;
use App\Models\Cases;
use App\Models\casesFiType;
use App\Models\FiType;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public $user;
    public $Auth;
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function show($id)
    {
        abort(404); // Or return a view if you want
    }
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('role.view')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view any role !');
        // }

        // $roles = Role::all();
        $status = CaseStatus::select('id', 'name')
        ->whereIn('name', ['New', 'Inprogress', 'Negative Resolved', 'Positive Resolved','Positive Verified', 'Negative Verified', 'Hold', 'close'])
        ->orderBy('name') // Example of adding ordering
        ->get();
        return view('backend.pages.reports.index', compact('status'));
    }

    public function fetchreport(Request $request)
    {
        $validated = $request->validate([
            'verify_type' => 'required',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
        ]);

        $status = $request->input('verify_type');
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        
        $query = casesFiType::with([
            'getCase:id,refrence_number,applicant_name,bank_id',
            'getCase.getBranch:id,branch_name,branch_code',
            'getFiType:id,name',
            'getUser:id,name',
            'getStatus:id,name', 
            'getCaseStatus:id,name',
            'getUserVerifiersName:id,name'
        ]);
        
        // Use the same bank filtering logic as fetchcountreport
        if (Auth::guard('admin')->user()->role != 'superadmin') {
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $query->whereHas('getCase', function ($query) use ($assignBank) {
                $query->whereIn("bank_id", $assignBank);
            });
        }
        
        // Use created_at for date filtering like fetchcountreport
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Keep the status filtering logic
        if ($status >= 0) {
            $query->where('status', $status);
        }
        
        $cases = $query->get();
        return view('backend.pages.reports.list', compact('cases'))->render();
    }
    public function export(Request $request)
    {
        $validated = $request->validate([
            'verify_type' => 'required',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
        ]);
        $filter['status'] = $request->input('verify_type');
        $filter['dateFrom'] = $request->input('dateFrom');
        $filter['dateTo'] = $request->input('dateTo');
        
        return Excel::download(new CasesExport($filter), 'cases_report.xlsx');
    }

    public function countReport(Request $request)
    {
        // Get all verifiers (Admin users who have verified cases)
        $verifiers = Admin::select('id', 'name')
            ->orderBy('name')
            ->get();
            
        return view('backend.pages.reports.countreport', compact('verifiers'));
    }
    public function fetchcountreport(Request $request)
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
            'report_type' => 'required|in:summary,daywise',
            'verifier_id' => 'nullable|exists:admins,id',
        ]);

        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $reportType = $request->input('report_type');
        $verifierId = $request->input('verifier_id');
        
        $query = casesFiType::with([
            'getCase', 
            'getUser:id,name',
            'getBranch',
            'getUserVerifiersName:id,name'
        ])->whereIn('status', ['4', '5', '7']); 
        
        if (Auth::guard('admin')->user()->role != 'superadmin') {
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $query->whereHas('getCase', function ($query) use ($assignBank) {
                $query->whereIn("bank_id", $assignBank);
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('updated_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('updated_at', '<=', $dateTo);
        }
        
        // Filter by verifier if selected
        if ($verifierId) {
            $query->where('verifiers_name', $verifierId);
        }
        
        if ($reportType === 'daywise') {
            // Day-wise report
            $cases = $query->get();
            $daywiseData = $cases->groupBy(function ($case) {
                return Carbon::parse($case->created_at)->format('Y-m-d');
            });
            
            // Sort dates in ascending order and restructure data
            $sortedData = collect();
            $sortedDates = $daywiseData->keys()->sort()->values();
            
            foreach ($sortedDates as $date) {
                $dayCases = $daywiseData[$date];
                $verifierGroups = $dayCases->groupBy(function ($case) {
                    return $case->getUserVerifiersName ? $case->getUserVerifiersName->name : 'Unassigned';
                });
                
                // Sort verifiers alphabetically 
                $sortedVerifiers = $verifierGroups->keys()->sort();
                
                $verifiersData = collect();
                foreach ($sortedVerifiers as $verifierName) {
                    $verifierCases = $verifierGroups[$verifierName];
                    $verifiersData->push([
                        'verifier' => $verifierName,
                        'count' => $verifierCases->count()
                    ]);
                }
                
                $sortedData->push([
                    'date' => $date,
                    'total_cases' => $dayCases->count(),
                    'verifiers' => $verifiersData
                ]);
            }
            
            return view('backend.pages.reports.countlist_daywise', compact('sortedData'))->render();
        } else {
            // Summary report (existing logic)
            $userCasesCount = $query->get()
                ->groupBy(function ($case) {
                    return $case->getUserVerifiersName ? $case->getUserVerifiersName->name : 'Unassigned';
                })
                ->map(function ($cases, $userName) {
                    return [
                        'user' => $userName,
                        'total_cases' => $cases->count()
                    ];
                });
                    
            return view('backend.pages.reports.countlist', compact('userCasesCount'))->render();
        }
    }
 
    public function exportcount(Request $request)
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
            'report_type' => 'required|in:summary,daywise',
            'verifier_id' => 'nullable|exists:admins,id',
        ]);
        $filter['dateFrom'] = $request->input('dateFrom');
        $filter['dateTo'] = $request->input('dateTo');
        $filter['report_type'] = $request->input('report_type');
        $filter['verifier_id'] = $request->input('verifier_id');
        
        return Excel::download(new CasesCountExport($filter), 'cases_count_report.xlsx');
    }

    public function billingReport()
    {
        // No need to pass $fitypes anymore since the view only uses date range
        return view('backend.pages.reports.option');
    }
    
public function fetchBillingReport(Request $request)
{
    $validated = $request->validate([
        'dateFrom' => 'required|date',
        'dateTo'   => 'required|date|after_or_equal:dateFrom',
    ]);

    $dateFrom = $request->input('dateFrom');
    $dateTo = $request->input('dateTo');

    $cases = CasesFiType::with([
        'getCase:id,refrence_number,applicant_name,bank_id,geo_limit',
        'getCase.getBranch:id,branch_name,branch_code',
        'getFiType:id,name',
        'getUser:id,name',
        'getStatus:id,name',
        'getCaseStatus:id,name',
    ])
    ->whereIn('status', ['4', '5', '7', '1155'])
    ->whereDate('created_at', '>=', $dateFrom)
    ->whereDate('created_at', '<=', $dateTo)
    ->paginate(50); // or any per-page value

    return view('backend.pages.reports.option_list', compact('cases'))->render();
}

public function exportBilling(Request $request)
{
    $validated = $request->validate([
        'dateFrom' => 'required|date',
        'dateTo'   => 'required|date|after_or_equal:dateFrom',
    ]);
    $filter = [
        'dateFrom' => $request->dateFrom,
        'dateTo'   => $request->dateTo,
    ];
    return Excel::download(new BillingExport($filter), 'billing_report.xlsx');
}
}