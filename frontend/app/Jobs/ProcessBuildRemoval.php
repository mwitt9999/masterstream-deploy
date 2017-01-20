<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\BuildRemovalService;
use Illuminate\Support\Facades\Log;

class ProcessBuildRemoval implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $operationData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($operationData)
    {
        $this->operationData = $operationData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $buildRemovalService = new BuildRemovalService($this->operationData);
        $result = $buildRemovalService->remove();

        if(!$result) {
            Log::info('Failed to process build removal job');
        } else {
            Log::info('Successfully completed build removal job');
        }
    }

    public function failed()
    {
//        $job = json_decode($this->job->getRawBody());
//
//        $data = ['status' => 'Failed'];
//        Operation::where('job_id', $job->id)->update($data);
//
//        Log::info($job);
    }
}
