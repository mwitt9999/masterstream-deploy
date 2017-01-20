<?php

namespace App\Services;

use App\Operation;
use App\Server;
use App\Pipeline;
use App\Site;
use Illuminate\Support\Facades\Redis;
use App\Exceptions\CustomException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Build;
use App\SitesBuilds;

class BuildService
{
    private $client;
    private $tasks;
    private $server;
    private $site;
    private $currentTask;
    private $buildDirectory;
    private $errorLog;
    private $infoLog;

    public function __construct($buildData){
        $sshClient = new SSHClient;
        $serverModel = new Server;
        $pipeline = new Pipeline;
        $site = new Site;
        $this->operation = new Operation;
        $this->sitesBuilds = new SitesBuilds;
        $this->build = new Build;
        $currentTimestamp = date('Ymd-his');

        $this->buildData = $buildData;
        $this->tasks = $pipeline->getTasksByPipelineId($buildData['pipeline_id']);
        $this->server = $serverModel->getServerById($buildData['server_id']);
        $this->site = $site->getSiteById($buildData['site_id']);

        $sshClient->connect($this->server->ip);
        $this->client = $sshClient->getClient();
        $this->client->setTimeout(240);
        $this->client->disableQuietMode();

        $this->buildDirectory = '/var/www/'.$this->site->github_repository_name.'/build/'.$this->buildData['version'].'-'.$this->buildData['commit_hash'].'-'.$currentTimestamp;
        $this->liveSiteDirectory = '/var/www/'.$this->site->github_repository_name.'/current';
        $this->sharedDirectory = '/var/www/'.$this->site->github_repository_name.'/shared';

        $this->infoLog = new Logger($this->site->github_repository_name);
        $this->infoLog->pushHandler(new StreamHandler(storage_path().'/logs/build-logs/'.$this->site->github_repository_name. '/'.$this->buildData['version'].'-'.$this->buildData['commit_hash'].'-'.$currentTimestamp .'/info.log', Logger::INFO));

        $this->errorLog = new Logger($this->site->github_repository_name);
        $this->errorLog->pushHandler(new StreamHandler(storage_path().'/logs/build-logs/'.$this->site->github_repository_name. '/'.$this->buildData['version'].'-'.$this->buildData['commit_hash'].'-'.$currentTimestamp .'/error.log', Logger::CRITICAL));
    }

    public function build()
    {
        if(!$this->client) {
            return false;
        }

        $this->createBuildDirectory();
        $this->cloneRepository();
        $this->checkoutCommit();

        foreach ($this->tasks as $key => $task) {

            $this->currentTask = $task;

            $this->publishTaskOutput('Starting: '.$task->output_message);
            $this->executeCurrentTask();
            $this->infoLog->addInfo($task->output_message);
        }

        $this->shareEnvFile();
        $this->deployBuildSite();

        $updatedOperationData = [
            'status' =>  'Completed',
            'build_directory' => $this->buildDirectory
        ];

        $build_id = $this->build->saveBuild($this->buildData['dispatch_id'], true);
        $this->sitesBuilds->saveSitesBuilds($this->buildData['site_id'], $build_id);

        $this->operation->editOperationByDispatchId($this->buildData['dispatch_id'], $updatedOperationData);
        $this->infoLog->addInfo('Completed Build');

        $this->publishTaskOutput('Completed Build: '.$this->buildData['version'].'-'.$this->buildData['commit_hash']);

        return true;
    }

    private function createBuildDirectory() {
        $task = (object)[
            'command' => 'sudo mkdir -p '.$this->buildDirectory,
            'output_message' => 'Creating Build Directory: '.$this->buildDirectory,
            'command_directory' => '',
            'run_from_build_directory' => false
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask();
        $this->infoLog->addInfo($task->output_message);
    }

    private function cloneRepository() {
        $task = (object)[
            'command' => 'sudo git clone git@github.com:'.$this->site->github_account_name.'/'.$this->site->github_repository_name.'.git .;',
            'output_message' => 'Cloning '.$this->site->github_repository_name,
            'run_from_build_directory' => true
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask();
        $this->infoLog->addInfo($task->output_message);
    }

    private function checkoutCommit() {
        $task = (object)[
            'command' => 'sudo git checkout '.$this->buildData['commit_hash'],
            'output_message' => 'Checking Out - '.$this->buildData['version'].':'.$this->buildData['commit_hash'],
            'run_from_build_directory' => true
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask();
        $this->infoLog->addInfo($task->output_message);
    }

    private function shareEnvFile() {
        $task = (object)[
            'command' => "sudo cp ".$this->sharedDirectory.'/.env '.$this->buildDirectory,
            'output_message' => 'Copying .env file to live site directory',
            'run_from_build_directory' => true
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask();
        $this->infoLog->addInfo($task->output_message);
    }

    private function deployBuildSite() {
        $task = (object)[
            'command' => "sudo ln -sfn ".$this->buildDirectory." ".$this->liveSiteDirectory,
            'output_message' => 'Deploying Build Site',
            'run_from_build_directory' => true
        ];

        $this->publishTaskOutput('Starting; '.$task->output_message);
        $this->currentTask = $task;
        $this->executeCurrentTask();
        $this->infoLog->addInfo($task->output_message);
    }

    private function executeCurrentTask() {

        if($this->currentTask->run_from_build_directory ?
            $command = 'cd '.$this->buildDirectory.'; '.$this->currentTask->command :
            $command = 'cd '.$this->currentTask->command_directory.'; '.$this->currentTask->command
        );

        $this->client->exec(
            $command, function ($taskOutput) {

                if($this->client->getExitStatus() == 1){
                    $updatedOperationData = [
                        'status' =>  'Failed',
                        'build_directory' => $this->buildDirectory
                    ];

                    $this->operation->editOperationByDispatchId($this->buildData['dispatch_id'], $updatedOperationData);
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
            'event' => 'ShowTerminalTaskResult',
            'data' => [
                'output' => $taskOutput,
            ]
        ];

        Redis::publish('terminal-output', json_encode($data));
    }
}