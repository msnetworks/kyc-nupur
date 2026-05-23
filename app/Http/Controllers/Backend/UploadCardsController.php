<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CaseStatus;
use App\Models\UpdateCardCommonFlow;
use App\Models\UploadCard;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UploadCardsController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view upload cards !');
        }

        $filters = [
            'agent_id' => $request->input('agent_id'),
            'status' => $request->input('status'),
        ];

        $uploadCards = UploadCard::with(['agent', 'masterStatus', 'commonFlow.verifier', 'commonFlow.closer'])
            ->when($filters['agent_id'] !== null && $filters['agent_id'] !== '', function ($query) use ($filters) {
                if ($filters['agent_id'] === 'unassigned') {
                    return $query->whereNull('agent_id');
                }

                return $query->where('agent_id', $filters['agent_id']);
            })
            ->when($filters['status'] !== null && $filters['status'] !== '', function ($query) use ($filters) {
                return $query->where('status', $filters['status']);
            })
            ->latest()
            ->get();
        $statusOptions = $this->getUploadCardChangeStatuses();
        $selectedAgent = !empty($filters['agent_id']) ? User::find($filters['agent_id']) : null;
        $selectedStatus = !empty($filters['status']) ? CaseStatus::find($filters['status']) : null;

        return view('backend.pages.upload_cards.index', compact('uploadCards', 'statusOptions', 'filters', 'selectedAgent', 'selectedStatus'));
    }

    public function dashboard(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view upload card dashboard !');
        }

        $agent = $request->input('agent');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $agentWiseCards = $this->getAgentWiseCardStatistics($fromDate, $toDate, $agent);
        $totalSum = $this->calculateUploadCardSummary($agentWiseCards);
        $agentLists = $this->getUploadCardAgentList();

        return view('backend.pages.upload_cards.dashboard', compact('agentWiseCards', 'totalSum', 'agentLists'));
    }

    public function dashboardFilter(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view upload card dashboard !');
        }

        $agentWiseCards = $this->getAgentWiseCardStatistics(
            $request->input('fromDate'),
            $request->input('toDate'),
            $request->input('agent')
        );
        $totalSum = $this->calculateUploadCardSummary($agentWiseCards);

        return view('backend.pages.upload_cards.dashboardtable', compact('agentWiseCards', 'totalSum'));
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('upload_card.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create upload cards !');
        }

        $statuses = $this->getUploadCardStatuses();
        return view('backend.pages.upload_cards.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create upload cards !');
        }

        $request->validate([
            'use_type' => 'required|in:web,mobile_app',
            'employee_code' => 'required|max:50|unique:upload_cards,employee_code',
            'name' => 'required|max:100',
            'address' => 'required|max:1000',
            'mobile_no' => 'required|digits:10',
            'aadhaar_card' => 'required|digits:12',
            'agent_id' => 'nullable|exists:users,id',
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $photoPath = $this->uploadPhoto($request);

        DB::transaction(function () use ($request, $photoPath) {
            $uploadCard = UploadCard::create([
                'use_type' => $request->use_type,
                'employee_code' => $request->employee_code,
                'name' => $request->name,
                'address' => $request->address,
                'mobile_no' => $request->mobile_no,
                'aadhaar_card' => $request->aadhaar_card,
                'photo' => $photoPath,
                'agent_id' => $request->agent_id,
                'status' => 1,
                'created_by' => $this->user->id,
                'updated_by' => $this->user->id,
            ]);

            $this->syncUploadCardCommonFlow($uploadCard, 1);
        });

        session()->flash('success', 'Upload card has been created !!');
        return redirect()->route('admin.upload-cards.index');
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit upload cards !');
        }

        $uploadCard = UploadCard::findOrFail($id);
        $statuses = $this->getUploadCardStatuses();
        return view('backend.pages.upload_cards.edit', compact('uploadCard', 'statuses'));
    }

    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view upload cards !');
        }

        return redirect()->route('admin.upload-cards.index');
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit upload cards !');
        }

        $uploadCard = UploadCard::findOrFail($id);

        $request->validate([
            'use_type' => 'required|in:web,mobile_app',
            'employee_code' => 'required|max:50|unique:upload_cards,employee_code,' . $id,
            'name' => 'required|max:100',
            'address' => 'required|max:1000',
            'mobile_no' => 'required|digits:10',
            'aadhaar_card' => 'required|digits:12',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'required|integer|in:1,4,5,7|exists:case_status,id',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = [
            'use_type' => $request->use_type,
            'employee_code' => $request->employee_code,
            'name' => $request->name,
            'address' => $request->address,
            'mobile_no' => $request->mobile_no,
            'aadhaar_card' => $request->aadhaar_card,
            'agent_id' => $request->agent_id,
            'status' => $request->status,
            'updated_by' => $this->user->id,
        ];

        if ($request->hasFile('photo')) {
            $this->deletePhoto($uploadCard->photo);
            $data['photo'] = $this->uploadPhoto($request);
        }

        DB::transaction(function () use ($uploadCard, $data) {
            $uploadCard->update($data);
            $this->syncUploadCardCommonFlow($uploadCard, (int) $data['status']);
        });

        session()->flash('success', 'Upload card has been updated !!');
        return redirect()->route('admin.upload-cards.index');
    }

    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete upload cards !');
        }

        $uploadCard = UploadCard::findOrFail($id);
        $this->deletePhoto($uploadCard->photo);
        $uploadCard->delete();

        session()->flash('success', 'Upload card has been deleted !!');
        return back();
    }

    public function updateStatus(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to change upload card status !');
        }

        $request->validate([
            'status' => 'required|integer|in:1,4,5,7|exists:case_status,id',
        ]);

        $uploadCard = UploadCard::findOrFail($id);

        DB::transaction(function () use ($uploadCard, $request) {
            $uploadCard->update([
                'status' => $request->status,
                'updated_by' => $this->user->id,
            ]);

            $this->syncUploadCardCommonFlow($uploadCard, (int) $request->status);
        });

        session()->flash('success', 'Upload card status has been updated !!');
        return back();
    }

    public function downloadPdf($id)
    {
        if (is_null($this->user) || !$this->user->can('upload_card.view')) {
            abort(403, 'Sorry !! You are Unauthorized to download upload card PDF !');
        }

        $uploadCard = UploadCard::with(['agent', 'masterStatus', 'commonFlow.verifier', 'commonFlow.closer'])->findOrFail($id);
        $view = view('backend.pages.upload_cards.pdf', compact('uploadCard'))->render();

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($view);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = $this->sanitizePdfFileNamePart($uploadCard->employee_code . '_' . $uploadCard->name) . '.pdf';
        $output = $dompdf->output();

        return response()->streamDownload(function () use ($output) {
            echo $output;
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function uploadPhoto(Request $request)
    {
        $path = 'uploads/upload-cards';

        if (!File::exists(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true);
        }

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $filename);

        return $path . '/' . $filename;
    }

    private function deletePhoto($photo)
    {
        if (!empty($photo) && File::exists(public_path($photo))) {
            File::delete(public_path($photo));
        }
    }

    private function getAgentWiseCardStatistics($fromDate = null, $toDate = null, $agent = null)
    {
        $query = UploadCard::query()
            ->select([
                'agent_id',
                DB::raw('COUNT(*) as total_cases'),
                DB::raw('SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as inprogress_cases'),
                DB::raw('SUM(CASE WHEN status = "7" THEN 1 ELSE 0 END) as closed_cases'),
                DB::raw('SUM(CASE WHEN status = "4" THEN 1 ELSE 0 END) as positive_verified_cases'),
                DB::raw('SUM(CASE WHEN status = "5" THEN 1 ELSE 0 END) as negative_verified_cases'),
            ])
            ->when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', $toDate);
            })
            ->when($agent !== null && $agent !== '', function ($query) use ($agent) {
                return $query->where('agent_id', $agent);
            })
            ->groupBy('agent_id')
            ->orderByRaw('agent_id IS NULL, agent_id ASC');

        $results = $query->get();
        $users = User::whereIn('id', $results->pluck('agent_id')->filter()->unique())->pluck('name', 'id');

        $agentWiseCards = [];
        foreach ($results as $row) {
            $agentId = $row->agent_id;
            $agentWiseCards[] = [
                'agentid' => $agentId,
                'agentName' => $agentId ? ($users[$agentId] ?? 'Unknown') : 'Unassigned',
                'total' => (int) $row->total_cases,
                'inprogress' => (int) $row->inprogress_cases,
                'closed' => (int) $row->closed_cases,
                'positive_verified' => (int) $row->positive_verified_cases,
                'negative_verified' => (int) $row->negative_verified_cases,
            ];
        }

        return $agentWiseCards;
    }

    private function calculateUploadCardSummary($agentWiseCards)
    {
        $totalSum = [
            'total' => 0,
            'inprogressTotal' => 0,
            'closedTotal' => 0,
            'positiveVerifiedTotal' => 0,
            'negativeVerifiedTotal' => 0,
        ];

        foreach ($agentWiseCards as $agentData) {
            $totalSum['total'] += $agentData['total'];
            $totalSum['inprogressTotal'] += $agentData['inprogress'];
            $totalSum['closedTotal'] += $agentData['closed'];
            $totalSum['positiveVerifiedTotal'] += $agentData['positive_verified'];
            $totalSum['negativeVerifiedTotal'] += $agentData['negative_verified'];
        }

        return $totalSum;
    }

    private function getUploadCardAgentList()
    {
        return User::whereIn('id', function ($query) {
            $query->select('agent_id')
                ->from('upload_cards')
                ->whereNotNull('agent_id')
                ->distinct();
        })->pluck('name', 'id')->toArray();
    }

    private function getUploadCardStatuses()
    {
        return $this->getUploadCardFlowStatuses();
    }

    private function getUploadCardChangeStatuses()
    {
        return $this->getUploadCardFlowStatuses();
    }

    private function getUploadCardFlowStatuses()
    {
        return CaseStatus::whereIn('id', [1, 4, 5, 7])
            ->orderByRaw('FIELD(id, 1, 4, 5, 7)')
            ->get();
    }

    private function syncUploadCardCommonFlow(UploadCard $uploadCard, $status)
    {
        $data = [
            'status' => $status,
        ];

        if ((int) $status === 1) {
            $data['verified_by'] = null;
            $data['verified_on'] = null;
            $data['close_by'] = null;
            $data['closed_on'] = null;
        }

        if (in_array($status, [4, 5])) {
            $data['verified_by'] = $this->user->id;
            $data['verified_on'] = now();
            $data['close_by'] = null;
            $data['closed_on'] = null;
        }

        if ((int) $status === 7) {
            $data['close_by'] = $this->user->id;
            $data['closed_on'] = now();
        }

        UpdateCardCommonFlow::updateOrCreate(['upload_card_id' => $uploadCard->id], $data);
    }

    private function sanitizePdfFileNamePart($value)
    {
        $value = preg_replace('/[^A-Za-z0-9\s_-]/', '', $value ?? '');
        $value = preg_replace('/[\s_-]+/', '_', trim($value, " \t\n\r\0\x0B_-"));

        return $value !== '' ? $value : 'upload_card';
    }
}
