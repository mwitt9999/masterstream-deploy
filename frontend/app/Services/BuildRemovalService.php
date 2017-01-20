<?php

namespace App\Services;

use App\Build;

use Illuminate\Support\Facades\Redis;
use App\Exceptions\CustomException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class BuildRemovalService
{
    private $client;
    private $server;
    private $currentTask;
    private $errorLog;
    private $infoLog;
    private $sshClient;

    public function __construct($removeBuildData){
        $this->removalBuildData = $removeBuildData;
        $this->sshClient = new SSHClient;
        $this->buildModel = new Build;

        $this->infoLog = new Logger("BuildRemoval");
        $this->infoLog->pushHandler(new StreamHandler(storage_path().'/logs/build-removal/info.log', Logger::INFO));

        $this->errorLog = new Logger("BuildRemoval");
        $this->errorLog->pushHandler(new StreamHandler(storage_path().'/logs/build-removal/error.log', Logger::CRITICAL));
    }

    public function remove()
    {
        foreach($this->removalBuildData as $removalData) {

            try {
                $this->sshClient->connect($removalData['server']->ip);
                $this->client = $this->sshClient->getClient();
                $this->client->setTimeout(240);
                $this->client->disableQuietMode();

            } catch (CustomException $customException) {
                $this->errorLog->addCritical($customException);
                throw new CustomException($customException);
            }

            if(!$this->client) {
                $this->errorLog->addCritical('Could not connect to ssh client');
                throw new CustomException('Could not connect to ssh client');
            }

            $this->removeBuildDirectory($removalData['operation']->build_directory);
            $this->buildModel->editBuildById($removalData['build']->id);
        }

        $this->infoLog->addInfo('Completed Removal');
        $this->publishTaskOutput('Completed Removal');

        return true;
    }

    private function removeBuildDirectory($directory) {
        $task = (object)[
            'command' => 'sudo rm -Rf '.$directory,
            'output_message' => 'Removing Build Directory: '.$directory,
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask($task);
        $this->publishTaskOutput('Completed: '.$task->output_message);
        $this->infoLog->addInfo($task->output_message);
    }

    private function executeCurrentTask($task) {
        $this->client->exec(
            $task->command, function ($taskOutput) {

                if($this->client->getExitStatus() == 1){
                    $this->errorLog->addCritical(json_encode($this->client->getStdError()));
                    throw new CustomException(json_encode($this->client->getStdError()));
                }

                $this->publishTaskOutput($taskOutput);
                $this->infoLog->addInfo(json_encode($taskOutput));
            }
        );

    }

    public function publishTaskOutput($taskOutput) {
        $data = [
            'event' => 'ShowBuildRemovalResult',
            'data' => [
                'output' => $taskOutput,
            ]
        ];

        Redis::publish('remove-builds', json_encode($data));
    }
}