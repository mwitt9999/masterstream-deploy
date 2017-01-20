<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRollbackPostRequest;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessOperation;
use Illuminate\Http\Request;
use App\Operation;
use App\Server;

class RollbackController extends Controller
{
    protected $github;
    protected $operation;
    protected $server;

    public function __construct(GitHubManager $github, Operation $operation, Server $server)
    {
        $this->github = $github;
        $this->github->setDefaultConnection('mine');

        $this->operation = $operation;
        $this->server = $server;
    }

    public function showRollback(Request $request) {
        $commits = $this->github->repo()->tags(getenv('GITHUB_ACCOUNT'), getenv('GITHUB_REPOSITORY'), array());
        $servers = $this->server->getAllServers();

        return view('rollback')->with('commits', $commits)->with('servers', $servers);
    }

    public function submitRollback(StoreRollbackPostRequest $request) {

        $userId = Auth::user()->id;

        foreach($request->input('server_id') as $serverId ) {
            $jobId = dispatch(new ProcessOperation('12121', $request->input('commit_hash') ));

            $this->operation->saveOperation((int)$serverId, $userId, $request->input('commit_hash'), $request->input('version'), 'rollback', $jobId);
        }

        return response()->json([
            'success' => 'true',
        ]);
    }
}