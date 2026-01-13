<?php

namespace App\Exports;

use App\Models\casesFiType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BillingExport implements FromCollection, WithHeadings, WithStyles
{
    protected $filter;
    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $cases = casesFiType::with([
            'getCase:id,refrence_number,applicant_name,bank_id,geo_limit',
            'getCase.getBranch:id,branch_name,branch_code',
            'getFiType:id,name',
            'getUser:id,name',
            'getStatus:id,name',
            'getCaseStatus:id,name',
        ])
        ->whereIn('status', ['4', '5', '7', '1155'])
        ->whereDate('created_at', '>=', $this->filter['dateFrom'])
        ->whereDate('created_at', '<=', $this->filter['dateTo'])
        ->get();

        $gst_percent = 0.18;
        
        $rows = [];
        $total_rate_sum = 0;
        $total_telecalling_sum = 0;
        $total_gst_sum = 0;
        $total_amount_sum = 0;
        
        foreach ($cases as $case) {
            $geoLimit = optional($case->getCase)->geo_limit;
            $rate = in_array($case->fi_type_id, [7, 8]) && $geoLimit === 'Outstation'
                ? 190
                : (in_array($case->fi_type_id, [12, 13, 14, 17]) ? 125 : 120);
            $telecalling = in_array($case->fi_type_id, [7, 8]) ? 10 : 0;

            $visit_multiplier = 1;
            if (in_array($case->fi_type_id, [12, 13]) && !empty($case->itr_form_remarks)) {
                $itr_remarks = json_decode($case->itr_form_remarks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($itr_remarks) && count($itr_remarks) > 0) {
                    $visit_multiplier = count($itr_remarks);
                }
            }

            $adjusted_rate = $rate * $visit_multiplier;
            $subtotal_before_gst = $adjusted_rate + $telecalling;
            $gst = $subtotal_before_gst * $gst_percent;
            $total = $subtotal_before_gst + $gst;

            if ($case->status == '1155') {
                $adjusted_rate = 0;
                $telecalling = 0;
                $gst = 0;
                $total = 0;
            }

            $total_rate_sum += $adjusted_rate;
            $total_telecalling_sum += $telecalling;
            $total_gst_sum += $gst;
            $total_amount_sum += $total;

            $rows[] = [
                optional($case->getCase)->refrence_number ?? '',
                optional($case->getCase)->applicant_name ?? '',
                $case->mobile ?? '',
                $case->address ?? '',
                optional($case->getFiType)->name ?? '',
                $this->formatItrRemarks($case->itr_form_remarks),
                $geoLimit ?? '',
                $case->pan_number ?? ($case->pan_card ?? '-'),
                $case->assessment_year ?? '-',
                $case->accountnumber ?? '-',
                $case->bank_name ?? '-',
                $case->status == 4 ? 'Positive' : ($case->status == 5 ? 'Negative' : 'Closed'),
                optional($case->created_at)->format('d-m-Y') ?? '',
                optional($case->getCaseStatus)->name ?? '',
                $adjusted_rate,
                $telecalling,
                $gst,
                $total,
            ];
        }

        $rows[] = array_merge(
            array_fill(0, 12, ''),
            ['Sub Total', $total_rate_sum, $total_telecalling_sum, $total_gst_sum, $total_amount_sum]
        );

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Enternal Code',
            'Name',
            'No.',
            'Address',
            'Type',
            'ITR Form Remarks',
            'Geo Limit',
            'Pan Card',
            'Assesment Year',
            'Account No',
            'Bank Reference No.',
            'Status',
            'Created At',
            'Remarks',
            'Rate',
            'Telecalling',
            'GST (18%)',
            'Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:R1')->getFont()->setBold(true);
    }

    protected function formatItrRemarks($remarks): string
    {
        if (empty($remarks)) {
            return '-';
        }

        $decoded = json_decode($remarks, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $flat = [];
            array_walk_recursive($decoded, function ($value) use (&$flat) {
                if (is_string($value) && trim($value) !== '') {
                    $flat[] = trim($value);
                }
            });

            return !empty($flat) ? implode(' | ', $flat) : '-';
        }

        return $remarks;
    }
}