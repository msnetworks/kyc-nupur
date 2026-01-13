<?php

namespace App\Exports;

use App\Models\casesFiType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Illuminate\Support\Facades\Auth;

class CasesCountExport implements FromCollection, WithHeadings, WithStyles
{
    protected $dateTo;
    protected $dateFrom;
    protected $reportType;
    protected $verifierId;

    public function __construct($filter)
    {
        $this->dateTo = $filter['dateTo'];
        $this->dateFrom = $filter['dateFrom'];
        $this->reportType = $filter['report_type'] ?? 'summary';
        $this->verifierId = $filter['verifier_id'] ?? null;
    }

    // public function collection()
    // {
    //     $dateFrom = $this->dateFrom;
    //     $dateTo = $this->dateTo;

    //     $query = casesFiType::with([
    //         'getCase:id,refrence_number,applicant_name,bank_id',
    //         'getUser:id,name',
    //         'getUserVerifiersName:id,name'
    //     ])->whereIn('status', [4, 5, 7]);

    //     if (Auth::guard('admin')->user()->id != 1) {
    //         $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
    //         $query->whereHas('getCase', function ($query) use ($assignBank) {
    //             $query->whereIn("bank_id", $assignBank);
    //         });
    //     }

    //     if ($dateFrom) {
    //         $query->whereDate('updated_at', '>=', $dateFrom);
    //     }

    //     if ($dateTo) {
    //         $query->whereDate('updated_at', '<=', $dateTo);
    //     }

    //     $userCasesCount = $query->get()
    //         ->groupBy('verified_by')
    //         ->map(function ($cases, $userName) {
    //             return [
    //                 'user' => $userName,
    //                 'total_cases' => $cases->count(),
    //             ];
    //         })
    //         ->reject(function ($item) {
    //             // Remove empty entries where user name is blank or null
    //             return empty($item['user']);
    //         })
    //         ->values(); // Reset array keys

    //     return collect($userCasesCount);
    // }

    public function collection()
    {
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;
        $reportType = $this->reportType;
        $verifierId = $this->verifierId;

        $query = casesFiType::with([
            'getCase:id,refrence_number,applicant_name,bank_id',
            'getUser:id,name',
            'getUserVerifiersName:id,name'
        ])->whereIn('status', ['4', '5', '7']);

        if (Auth::guard('admin')->user()->id != 1) {
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
            // Day-wise report export
            $cases = $query->get();
            $exportData = collect();
            
            $daywiseData = $cases->groupBy(function ($case) {
                return \Carbon\Carbon::parse($case->updated_at)->format('Y-m-d');
            });
            
            // Sort dates in ascending order
            $sortedDates = $daywiseData->keys()->sort()->values();
            
            foreach ($sortedDates as $date) {
                $dayCases = $daywiseData[$date];
                $verifierGroups = $dayCases->groupBy(function ($case) {
                    return $case->getUserVerifiersName ? $case->getUserVerifiersName->name : 'Unassigned';
                });
                
                // Sort verifiers alphabetically
                $sortedVerifiers = $verifierGroups->keys()->sort()->values();
                
                foreach ($sortedVerifiers as $verifierName) {
                    $verifierCases = $verifierGroups[$verifierName];
                    $exportData->push([
                        'date' => \Carbon\Carbon::parse($date)->format('d-m-Y'),
                        'verifier' => $verifierName,
                        'count' => $verifierCases->count()
                    ]);
                }
            }
            
            return $exportData;
        } else {
            // Summary report export (existing logic)
            $userCasesCount = $query->get()
                ->groupBy(function ($case) {
                    return $case->getUserVerifiersName ? $case->getUserVerifiersName->name : 'Unassigned';
                })
                ->map(function ($cases, $userName) {
                    return [
                        'user' => $userName,
                        'total_cases' => $cases->count(),
                    ];
                })
                ->reject(function ($item) {
                    // Only remove entries where user is 'Unassigned' if needed
                    return $item['user'] === 'Unassigned';
                })
                ->values(); // Reset array keys

            return collect($userCasesCount);
        }
    }

    public function headings(): array
    {
        if ($this->reportType === 'daywise') {
            return [
                'Date',
                'Verifier Name',
                'Case Count',
            ];
        } else {
            return [
                'User Name',
                'Case Count',
            ];
        }
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
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
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        $columnRange = $this->reportType === 'daywise' ? 'A1:C1' : 'A1:B1';
        $sheet->getStyle($columnRange)->applyFromArray($headerStyle);

        $rowCount = $this->collection()->count() + 1;
        $dataRange = $this->reportType === 'daywise' ? 'A1:C' . $rowCount : 'A1:B' . $rowCount;
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);
    }
}
