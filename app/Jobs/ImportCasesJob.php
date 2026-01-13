<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Jobs\HandleFailedValidationJob;
use App\Models\Cases;
use App\Models\BranchCode;
use App\Models\FiType;
use App\Models\casesFiType;
use App\Models\Product;
use App\Models\User;
use Auth;
use DateTime;
use Validator;
use Excel;
use Exception;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportCasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $params;

    public function __construct($filePath, $params)
    {
        $this->filePath = $filePath;  // Store the file path
        $this->params = $params;      // Store additional parameters
    }

    public function handle()
    {
        $file = Storage::path($this->filePath);
        $rows = Excel::toArray([], storage_path('app/' . $this->filePath));
        $failedRows = []; // Array to store failed rows and their errors
    
        foreach ($rows[0] as $key => $row) {
            $row[13] = intval($row[13]);
            $row[11] = intval($row[11]);
            $row[12] = intval($row[12]);
            $data = [
                'mobile'  => $row[13],
                'pincode' => $row[11],
                'landline' => $row[12],
                'product_id' => $row[15],
                'branch_code' => $row[16],
                'verification_type' => $row[16],
            ];
    
            if ($key > 0 && !empty($row[0])) {
                $validator = Validator::make($data, [
                    'mobile'   => 'digits:10',
                    'pincode'  => 'digits:6',
                    'product_id'  => 'required',
                    'branch_code'  => 'required',
                    'verification_type'  => 'required',
                ]);
    
                if ($validator->fails()) {
                    // Collect the failed row and errors
                    $failedRows[] = [
                        'row' => $row,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    continue;
                } else {
                    $branch_code = $row[16];
                    $branch = BranchCode::where('branch_code', $branch_code)->first();
    
                    $product_code = $row[15];
                    $product = Product::where('product_code', $product_code)->first();
    
                    if (!$branch || !$product) {
                        // Collect the failed row and error message
                        $failedRows[] = [
                            'row' => $row,
                            'errors' => ["Branch or Product not found for given codes: Branch Code {$branch_code}, Product Code {$product_code}"],
                        ];
                        continue;
                    }
    
                    $tatstart = $this->convertExcelDate($row[17]);
                    $tatend = $this->convertExcelDate($row[18]);
    
                    // Generate reference number
                    $existing_cases_count = Cases::where('branch_code', $branch_code)->count();
                    $automatic_number = str_pad($existing_cases_count + 1, 5, '0', STR_PAD_LEFT); // 5-digit padded number
                    $reference_number = "SRM/{$branch_code}/{$automatic_number}";
    
                    try {
                        $cases = new Cases();
                        $cases->bank_id             = $this->params['bank_id'];
                        $cases->product_id          = $product->id;
                        $cases->branch_code         = $branch->id;
                        $cases->application_type    = '3';
                        $cases->refrence_number     = $reference_number;
                        $cases->applicant_name      = $row[5];
                        $cases->tat_start           = $tatstart;
                        $cases->tat_end             = $tatend;
                        $cases->amount              = '0';
                        $cases->created_by          = Auth::guard('admin')->user()->id;
                        $cases->updated_by          = 0;
                        $cases->save();
                        $cases_id = $cases->id;
    
                        // Additional data processing for FiType and casesFiType...
    
                    } catch (\Exception $e) {
                        // Collect the failed row and error message if saving fails
                        $failedRows[] = [
                            'row' => $row,
                            'errors' => [$e->getMessage()],
                        ];
                        continue;
                    }
                }
            }
        }
    
        Storage::delete($this->filePath);
    
        // Return a summary of failed rows
        if (!empty($failedRows)) {
            return response()->json([
                'message' => 'Processing completed with errors. <br>',
                'failed_rows' => $failedRows,
            ], 400);
        }
    
        return response()->json([
            'message' => 'Processing completed successfully.',
        ], 200);
    }
    
    
    private function convertExcelDate($value)
    {
        // Check if it's a numeric value (which Excel uses for dates)
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value));  // Convert Excel date to Carbon
        }
        
        // If it's not numeric (like already in a date format), return as it is
        return Carbon::parse($value);
    }
    public function failed($exception)
    {
        // Log the error
        Log::error('ImportCasesJob failed: ' . $exception->getMessage());
    }
}
