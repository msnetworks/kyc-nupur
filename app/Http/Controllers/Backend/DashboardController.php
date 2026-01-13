<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\casesFiType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Cases;
use DateTime;
use DB;

class DashboardController extends Controller
{
    public $user;
    public $FromDate;
    public $ToDate;
    
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            $this->FromDate = $this->ToDate = date('Y-m-d');

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard!');
        }
        
        if (Auth::guard('admin')->user()->role === 'Bank') {
            return redirect()->route('admin.case.caseStatus', ['status' => 'aaa','user_id' => 0]);
        }

        if ($request->FromDate) {
            $FromDate = $request->FromDate;
            $ToDate = $request->ToDate;
        } else {
            $FromDate = null;
            $ToDate = null;
        }

        // Basic counts - these are fine as they're simple counts
        $total_roles = Role::count();
        $total_admins = Admin::count();
        $total_permissions = Permission::count();
        $total_Unassigned = casesFiType::where('status', '0')->count();
        $total_dedup = casesFiType::where('status', '8')->count();

        // Get user case statistics using efficient database aggregation
        $userCount = $this->getUserCaseStatistics($FromDate, $ToDate);
        $totalSum = $this->calculateTotalSummary($userCount);
        $agentLists = $this->getAgentList();

        return view('backend.pages.dashboard.index', compact('totalSum', 'userCount', 'agentLists', 'total_Unassigned', 'total_dedup'));
    }

    /**
     * Get user case statistics using efficient database queries
     */
    private function getUserCaseStatistics($fromDate = null, $toDate = null)
    {
        $today = date('Y-m-d');
        
        // Alternative approach: Use chunking to avoid memory issues
        $userCount = [];
        $totalSum = [
            'total' => 0,
            'inprogressTotal' => 0,
            'positive_resolvedTotal' => 0,
            'negative_resolvedTotal' => 0,
            'positive_verifiedTotal' => 0,
            'negative_verifiedTotal' => 0,
            'holdTotal' => 0,
            'closeTotal' => 0,
        ];

        // Process data in chunks to avoid memory issues
        casesFiType::with('getuser:id,name')
            ->where('user_id', '!=', '0')
            ->whereNotNull('user_id')
            ->when($fromDate, function($query, $fromDate) {
                return $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function($query, $toDate) {
                return $query->whereDate('created_at', '<=', $toDate);
            })
            ->chunk(100000, function($cases) use (&$userCount, $today) {
                foreach ($cases as $case) {
                    $userId = $case->user_id;
                    $status = $case->status;
                    $updatedAt = $case->updated_at ? $case->updated_at->format('Y-m-d') : null;
                    
                    // Initialize user data if not exists
                    if (!isset($userCount[$userId])) {
                        $userCount[$userId] = [
                            'created_by' => $userId,
                            'agentid' => $case->getuser ? $case->getuser->id : null,
                            'agentName' => $case->getuser ? $case->getuser->name : 'Unknown',
                            'inprogress' => 0,
                            'positive_resolved' => 0,
                            'negative_resolved' => 0,
                            'positive_verified' => 0,
                            'negative_verified' => 0,
                            'hold' => 0,
                            'close' => 0,
                            'total' => 0,
                        ];
                    }
                    
                    // Count based on status (matching your original logic)
                    switch ($status) {
                        case 1:
                            $userCount[$userId]['inprogress']++;
                            break;
                        case 2:
                            $userCount[$userId]['positive_resolved']++;
                            break;
                        case 3:
                            $userCount[$userId]['negative_resolved']++;
                            break;
                        case 4:
                            if ($updatedAt === $today) {
                                $userCount[$userId]['positive_verified']++;
                            }
                            break;
                        case 5:
                            if ($updatedAt === $today) {
                                $userCount[$userId]['negative_verified']++;
                            }
                            break;
                        case 6:
                            $userCount[$userId]['hold']++;
                            break;
                        case 7:
                            if ($updatedAt === $today) {
                                $userCount[$userId]['close']++;
                            }
                            break;
                    }
                    
                    // Update total for this user
                    $userCount[$userId]['total'] = $userCount[$userId]['inprogress'] + 
                                                  $userCount[$userId]['positive_resolved'] + 
                                                  $userCount[$userId]['negative_resolved'] + 
                                                  $userCount[$userId]['positive_verified'] + 
                                                  $userCount[$userId]['negative_verified'] + 
                                                  $userCount[$userId]['hold'] + 
                                                  $userCount[$userId]['close'];
                }
            });
        \Log::info('User counts:', $userCount);
        return $userCount;
    }

    /**
     * Calculate total summary from user statistics
     */
    private function calculateTotalSummary($userCount)
    {
        $totalSum = [
            'total' => 0,
            'inprogressTotal' => 0,
            'positive_resolvedTotal' => 0,
            'negative_resolvedTotal' => 0,
            'positive_verifiedTotal' => 0,
            'negative_verifiedTotal' => 0,
            'holdTotal' => 0,
            'closeTotal' => 0,
        ];

        foreach ($userCount as $userData) {
            $totalSum['total'] += $userData['total'];
            $totalSum['inprogressTotal'] += $userData['inprogress'];
            $totalSum['positive_resolvedTotal'] += $userData['positive_resolved'];
            $totalSum['negative_resolvedTotal'] += $userData['negative_resolved'];
            $totalSum['positive_verifiedTotal'] += $userData['positive_verified'];
            $totalSum['negative_verifiedTotal'] += $userData['negative_verified'];
            $totalSum['holdTotal'] += $userData['hold'];
            $totalSum['closeTotal'] += $userData['close'];
        }

        return $totalSum;
    }

    /**
     * Get agent list efficiently
     */
    private function getAgentList()
    {
        return User::whereIn('id', function($query) {
            $query->select('user_id')
                  ->from('cases_fi_types')
                  ->where('user_id', '!=', '0')
                  ->whereNotNull('user_id')
                  ->distinct();
        })->pluck('name', 'id')->toArray();
    }

    public function filter(Request $request)
    {
        $agent = $request->input('agent');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Use the same efficient method but with filters
        $userCount = $this->getUserCaseStatisticsFiltered($fromDate, $toDate, $agent);
        $totalSum = $this->calculateTotalSummary($userCount);

        return view('backend.pages.dashboard.dashboardtable', compact('totalSum', 'userCount'));
    }

    /**
     * Get filtered user case statistics
     */
    // private function getUserCaseStatisticsFiltered($fromDate = null, $toDate = null, $agent = null)
    // {
    //     $today = date('Y-m-d');
        
    //     $query = casesFiType::query()
    //         ->select([
    //             'user_id',
    //             DB::raw('COUNT(*) as total_cases'),
    //             DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as inprogress'),
    //             DB::raw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as positive_resolved'),
    //             DB::raw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as negative_resolved'),
    //             DB::raw('SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as positive_verified'),
    //             DB::raw('SUM(CASE WHEN status = 5 THEN 1 ELSE 0 END) as negative_verified'),
    //             DB::raw('SUM(CASE WHEN status = 6 THEN 1 ELSE 0 END) as hold'),
    //             DB::raw('SUM(CASE WHEN status = 7 THEN 1 ELSE 0 END) as close')
    //         ])
    //         ->where('user_id', '!=', '0')
    //         ->whereNotNull('user_id')
    //         ->groupBy('user_id');

    //     // Apply filters
    //     if ($fromDate) {
    //         $query->whereDate('created_at', '>=', $fromDate);
    //     }
    //     if ($toDate) {
    //         $query->whereDate('created_at', '<=', $toDate);
    //     }
    //     if ($agent) {
    //         $query->where('user_id', $agent);
    //     }

    //     $results = $query->get();

    //     // Get user information separately
    //     $userIds = $results->pluck('user_id')->unique();
    //     $users = User::whereIn('id', $userIds)->pluck('name', 'id');

    //     // Format results
    //     $userCount = [];
    //     foreach ($results as $row) {
    //         $userCount[$row->user_id] = [
    //             'created_by' => $row->user_id,
    //             'agentid' => $row->user_id,
    //             'agentName' => $users[$row->user_id] ?? 'Unknown',
    //             'inprogress' => (int) $row->inprogress,
    //             'positive_resolved' => (int) $row->positive_resolved,
    //             'negative_resolved' => (int) $row->negative_resolved,
    //             'positive_verified' => (int) $row->positive_verified,
    //             'negative_verified' => (int) $row->negative_verified,
    //             'hold' => (int) $row->hold,
    //             'close' => (int) $row->close,
    //             'total' => (int) $row->total_cases,
    //         ];
    //     }

    //     return $userCount;
    // }


    /**
 * Get filtered user case statistics
 */
private function getUserCaseStatisticsFiltered($fromDate = null, $toDate = null, $agent = null)
{
    $today = date('Y-m-d');
    
    $query = casesFiType::query()
        ->select([
            'user_id',
            DB::raw('COUNT(*) as total_cases'),
            // These three should count ALL cases (no date restriction)
            DB::raw('SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as inprogress'),
            DB::raw('SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) as positive_resolved'),
            DB::raw('SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) as negative_resolved'),
            // These three should only count cases updated TODAY
            DB::raw('SUM(CASE WHEN status = "4" AND DATE(updated_at) = "' . $today . '" THEN 1 ELSE 0 END) as positive_verified'),
            DB::raw('SUM(CASE WHEN status = "5" AND DATE(updated_at) = "' . $today . '" THEN 1 ELSE 0 END) as negative_verified'),
            DB::raw('SUM(CASE WHEN status = "7" AND DATE(updated_at) = "' . $today . '" THEN 1 ELSE 0 END) as close'),
            // Hold should count all cases
            DB::raw('SUM(CASE WHEN status = "6" THEN 1 ELSE 0 END) as hold')
        ])
        ->where('user_id', '!=', '0')
        ->whereNotNull('user_id')
        ->groupBy('user_id');

    // Apply filters only for created_at date range
    if ($fromDate) {
        $query->whereDate('created_at', '>=', $fromDate);
    }
    if ($toDate) {
        $query->whereDate('created_at', '<=', $toDate);
    }
    if ($agent) {
        $query->where('user_id', $agent);
    }

    $results = $query->get();

    // Get user information separately
    $userIds = $results->pluck('user_id')->unique();
    $users = User::whereIn('id', $userIds)->pluck('name', 'id');

    // Format results
    $userCount = [];
    foreach ($results as $row) {
        $userCount[$row->user_id] = [
            'created_by' => $row->user_id,
            'agentid' => $row->user_id,
            'agentName' => $users[$row->user_id] ?? 'Unknown',
            'inprogress' => (int) $row->inprogress,
            'positive_resolved' => (int) $row->positive_resolved,
            'negative_resolved' => (int) $row->negative_resolved,
            'positive_verified' => (int) $row->positive_verified,
            'negative_verified' => (int) $row->negative_verified,
            'hold' => (int) $row->hold,
            'close' => (int) $row->close,
            'total' => (int) $row->total_cases,
        ];
    }

    return $userCount;
}
}