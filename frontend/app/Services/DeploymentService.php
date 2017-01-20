<?php

namespace App\Services;

use App\Server;
use Illuminate\Support\Facades\Redis;

class DeploymentService
{
    protected $sshClient;
    private $tasks;
    private $currentTask;

    public function __construct(SSHClient $sshClient){
        $this->sshClient = $sshClient;

        $tasks[] = [
            'command' => 'cd /var/www; sudo rm -Rf html',
            'type' => '',
            'output_desc' => 'Removing old document root'
        ];
        $tasks[] = [
            'command' => 'cd /var/www; sudo mkdir html',
            'type' => '',
            'output_desc' => 'Recreating document root'
        ];
        $tasks[] = [
            'command' => 'cd /var/www/html; sudo git clone git@github.com:mwitt9999/test-deployment-app.git .',
            'type' => '',
            'output_desc' => 'Cloning test deployment app'
        ];
        $tasks[] = [
            'command' => 'cd /var/www/html; sudo git checkout %s',
            'type' => 'checkout',
            'output_desc' => 'Checking out commit: '
        ];
        $tasks[] = [
            'command' => 'cd /var/www/html; sudo composer install --no-interaction --no-dev --prefer-dist',
            'type' => '',
            'output_desc' => 'Installing Composer Dependencies'
        ];
        $tasks[] = [
            'command' => 'cd /var/www/html; sudo cp .env.example .env',
            'type' => '',
            'output_desc' => 'Copying .env to project root'
        ];
        $tasks[] = [
            'command' => 'cd /var/www/html; sudo chown -R 1000:1000 public; sudo chmod -R 0777 public',
            'type' => '',
            'output_desc' => 'Updating directory permissions'
        ];

        $this->setTasks($tasks);
    }

    public function build($serverId, $commitHash, Server $serverModel)
    {

        $server = $serverModel->getServerById($serverId);

        $this->sshClient->connect($server['ip']);
        $client = $this->sshClient->getClient();

        if(!$client) {
            return false;
        }

        $tasks = $this->getTasks();

        $client->setTimeout(240);

        foreach ($tasks as $key => $task) {

            if($task['type'] == 'checkout')
                $task['command'] = $this->updateTaskWithUserInputArray($task, [$commitHash]);

            $this->currentTask = $task;
//            $this->publishTaskOutput($task);
//            continue;
            $this->publishTaskOutput($task['output_desc']);
            $this->executeTask($client);
            $this->publishTaskOutput('Completed: '.$task['output_desc']);
        }

        $this->publishTaskOutput('Completed Deployment');

        return true;
    }

    private function getTasks() {
        return $this->tasks;
    }

    private function setTasks($tasks) {
        $this->tasks = $tasks;
    }

    public function updateTaskWithUserInputArray($task, $userInput) {
        return vsprintf($task['command'], $userInput);
    }

    private function executeTask($client) {
        $client->exec(
            $this->currentTask['command'], function ($taskOutput) {
            $this->publishTaskOutput($taskOutput);
        });
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