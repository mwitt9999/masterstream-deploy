<?php

namespace App\Jobs;

use App\Operation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\DeploymentService;
use Illuminate\Support\Facades\Log;

class ProcessDeployment implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $serverId;
    protected $commitHash;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($serverId, $commitHash)
    {
        $this->commitHash = $commitHash;
        $this->serverId = $serverId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DeploymentService $deploymentService)
    {
//        Log::info('Starting to process deployment...');
//
//        Log::info($this->serverId);
//        Log::info($this->commitHash);
        Log::info($this->job->getRaasdfsdwBody());



//        $result = $deploymentService->build($this->serverId, $this->commitHash);

//        if($result) {
//            Log::info('Failed to process deployment job');
//        } else {
//            Log::info('Successfully completed deployment job');
//        }
    }

    public function failed(Exception $exception)
    {
        $job = json_decode($this->job->getRawBody());

        $data = ['status' => 'Failed'];
        Operation::where('job_id', $job->id)->update($data);
    }
}
