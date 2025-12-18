<?php

namespace App\Exports;

use App\Models\casesFiType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use  Maatwebsite\Excel\Concerns\WithHeadings;
use  Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;


class ExportCase implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    protected $status;
    protected $user_id;

    public function __construct($status = null, $user_id = null)
    {
        $this->status = $status;
        $this->user_id = $user_id;
    }

    public function collection()
    {
        $user = Auth::guard('admin')->user();
        $query = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus']);
        $status = $this->status;
        $user_id = $this->user_id;
        // Apply filters if provided
        
        // if ($this->status !== null) {
        //     $query->where('status', $this->status);
        // }

        // if ($this->user_id !== null) {
        //     $query->where('user_id', $this->user_id);
        // }


        // Role-based filtering
        if ($user->role === 'superadmin') {
            if ($status !== 'aaa') {
                $query->where('status', $status);
            }
            if ($user_id != 0) {
                $query->where('user_id', $user_id);
            }
        } else {
            if ($user->role === 'Bank') {
                $query->whereHas('getCase', function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                });
            }
            if ($user_id != 0) {
                $query->where('user_id', $user_id);
            }
            $assignBank = explode(',', $user->banks_assign);
            $query->whereHas('getCase', function ($query) use ($assignBank) {
                $query->whereIn('bank_id', $assignBank);
            });
    
            if ($status !== 'aaa') {
                $query->where('status', $status);
            }
        }

        $case = $query->get();
        $output = [];

        if ($case) {
            $i = 0;
            foreach ($case as $value) {
                $fiType = $value->getFiType->name ?? null;
                $bank = $value->getCase->getBank->name ?? null;
                $product = $value->getCase->getProduct->name ?? null;
                $columnValue = null;

                if ($bank) {
                    $columnValue = $bank;
                }
                if ($product) {
                    $columnValue = $columnValue ? $columnValue . ' ' . $product : $product;
                }
                if ($fiType) {
                    $columnValue = $columnValue ? $columnValue . ' ' . $fiType : $fiType;
                }
                $output[$i][] = $value->id;
                $output[$i][] = $value->getCase->refrence_number ?? '';
                $output[$i][] = $value->applicant_name ?? ($value->getCase->applicant_name ?? '');
                $output[$i][] = $value->mobile ?? '';
                $address =  $value->address .' '. (isset($value->pincode) && $value->pincode != 0  ? 'Pincode: '.$value->pincode : '')  .' '.
                (isset($value->pan_number) ? 'Pan Card: '.$value->pan_number : '') .' '.
                (isset($value->assessment_year) ? 'Assesment Year: '.$value->assessment_year : '')  .' '.
                (isset($value->bank_name) ? 'Verify Bank Name '.$value->bank_name : '')  .' '.
                (isset($value->accountnumber) ? 'Account No. '.$value->accountnumber : '');
                $output[$i][] = $address ?? '';

                $output[$i][] = $address ?: '';
                $output[$i][] = $columnValue;
                $output[$i][] = $value->scheduled_visit_date ? humanReadableDate($value->scheduled_visit_date) : '';
                $output[$i][] = $user->role != 'Bank' ? ($value->getUser->name ?? '') : '';
                $output[$i][] = $value->status ? get_status($value->status) : '';
                $output[$i][] = $value->getCaseStatus->name ?? '';
                $output[$i][] = '';
                $i++;
            }
        }

        return collect($output);
    }



    public function headings(): array
    {
        return [
            'App Id',
            'Internal Code',
            'Name',
            'Mobile Number',
            'Address',
            'FIType',
            'Scheduled Date',
            'Agent',
            'Status',
            'SubStatus',
            'Action'

        ];
    }

    public function title(): string
    {
        return 'Cases FI Type';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')
            ->getFont()
            ->setBold(true)
            ->setSize(12)
            ->getColor()
            ->setRGB('ffffff');

        $sheet->getRowDimension('1')->setRowHeight(20);
        $sheet->getStyle('A1:K1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF0000');
    }
}
