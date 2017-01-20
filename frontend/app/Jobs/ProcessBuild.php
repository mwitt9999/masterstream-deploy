<?php

namespace App\Jobs;

use App\Operation;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\BuildService;
use Illuminate\Support\Facades\Log;

class ProcessBuild implements ShouldQueue
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
        $buildService = new BuildService($this->operationData);
        $result = $buildService->build();

        if(!$result) {
            Log::info('Failed to process build job');
        } else {
            Log::info('Successfully completed build job');
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
