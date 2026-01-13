<?php

namespace App\Exports;

use App\Models\casesFiType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class CasesExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $dateFrom;
    protected $dateTo;
    protected $status;
    public function __construct($filter)
    {
        $this->dateFrom = $filter['dateFrom'];
        $this->dateTo = $filter['dateTo'];
        $this->status = $filter['status'];
    }
    public function collection()
    {
        $query = casesFiType::with([
            'getCase:id,refrence_number,applicant_name,bank_id,product_id',
            'getCase.getBranch:id,branch_name,branch_code', // Add getBranch through getCase like in ReportsController
            'getCase.getProduct:id,name',
            'getBranch:id,branch_name,branch_code',
            'getFiType:id,name',
            'getUser:id,name',
            'getStatus:id,name',
            'getCaseStatus:id,name',
            'getUserVerifiersName:id,name' // Add this relationship like in ReportsController
        ]);
        // Use the same bank filtering logic as ReportsController fetchcountreport
        if(Auth::guard('admin')->user()->role != 'superadmin'){
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $query->whereHas('getCase', function ($query) use ($assignBank) {
                $query->whereIn("bank_id", $assignBank);
            });
        }
        
        // Use updated_at for date filtering to match fetchcountreport logic
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
            // $query->whereDate('updated_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            // $query->whereDate('updated_at', '<=', $this->dateTo);
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Match the logic from ReportsController and Dashboard
        if ((int)$this->status >= 0) {
            $query->where('status', (string)$this->status);
        }
        return $query->get()->map(function ($case) {
            return [ 
                'PROPOSAL NO.' => optional($case->getCase)->refrence_number,
                'APPLICANT NAME' => optional($case->getCase)->applicant_name,
                'VERIFICATION TYPE' => optional($case->getFiType)->name,
                'BRANCH CODE' => optional($case->getBranch)->branch_name ? ' (' . optional($case->getBranch)->branch_name . ')' : '',
                'PRODUCT' => optional($case->getCase)->getProduct?->name,
                'ADDRESS' => $case->address,
                'CITY' => $case->city ?? '-',
                'Mobile Number' => $case->mobile,
                'Pan Card' => $case->pan_number ?? ($case->pan_card ?? '-'),
                'Assesment Year' => $case->assessment_year ?? '-',
                'Aadhar Card' => $case->aadhar_card ?? ($case->getCase->aadhar_card ?? '-'),
                'Account No' => $case->accountnumber ?? '-',
                'Bank Reference No.' => $case->bank_name ?? '-',
                'DDA Reference No.' => $case->dd_ref_no ?? '-',
                'Date of Receipt to File' => $case->assement_date ?? '-',
                'Overall Status' => $case->overall_status == 2 ? 'Positive' : ($case->overall_status == 3 ? 'Negative' : $case->getStatus->name),
                'Status' => ucfirst(optional($case->getStatus)->name),
                'Verifier Remark' => $case->supervisor_remarks,
                'SubStatus' => ucfirst(optional($case->getCaseStatus)->name),
                'Office CPV Comment' => $case->app_remarks ?? '-',
                // 'Uploaded Date' => Carbon::parse($case->updated_at)->format('d-m-Y'),
                // 'Uploaded Time' => Carbon::parse($case->updated_at)->format('H:i:s'),
                'Created Date' => $case->created_at ? Carbon::parse($case->created_at)->format('d-m-Y') : '-',
                'Verified Date' => $case->verified_at ? Carbon::parse($case->verified_at)->format('d-m-Y') : '-',
                // 'Date of Visit' => $case->date_of_visit,
                // 'Visit Time' => $case->time_of_visit,
                'Agent Name' => optional($case->getUser)->name,
                'Agent Remark' => $case->consolidated_remarks,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'PROPOSAL NO.',
            'APPLICANT NAME',
            'VERIFICATION TYPE',
            'BRANCH CODE',
            'PRODUCT',
            'ADDRESS',
            'CITY',
            'Mobile Number',
            'Pan Card',
            'Assesment Year',
            'Aadhar Card',
            'Account No',
            'Bank Reference No.',
            'DDA Reference No.',
            'Date of Receipt to File',
            'Overall Status',
            'Status',
            'Verifier Remark',
            'SubStatus',
            'Office CPV Comment',
            // 'Uploaded Date',
            // 'Uploaded Time',
            'Created Date',
            'Verified Date',
            // 'Date of Visit',
            // 'Visit Time',
            'Agent Name',
            'Agent Remark'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE], // Header text color
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD'], // Header background color
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK], // Ensure full namespace
                ],
            ],

        ];
        
        
        $sheet->getStyle('A1:Z1')->applyFromArray($headerStyle); // Adjust columns as necessary
        
        $sheet->getStyle('A1:Z' . (count($this->collection())+1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);
    }
}
