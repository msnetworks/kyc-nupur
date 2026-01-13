<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandleFailedValidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $rowData;
    public $errors;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rowData, $errors)
    {
        $this->rowData = $rowData;
        $this->errors = $errors;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log the failed validation or take any action as required
        Log::error('Validation failed for row:', [
            'row_data' => $this->rowData,
            'errors' => $this->errors,
        ]);

        // You can also save it to the database, notify users, etc.
    }
}
