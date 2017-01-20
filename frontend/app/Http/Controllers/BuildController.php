<?php

namespace App\Http\Controllers;

use App\Build;
use App\Http\Requests\StoreBuildPostRequest;
use App\Jobs\ProcessBuildRemoval;
use App\Pipeline;
use App\Site;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessBuild;
use Illuminate\Http\Request;
use App\Operation;
use App\Server;
use App\Services\BuildService;
use Ramsey\Uuid\Uuid;

class BuildController extends Controller
{
    protected $github;
    protected $operation;
    protected $server;
    protected $site;
    protected $pipeline;
    protected $build;

    public function __construct(GitHubManager $github, Operation $operation, Server $server, Site $site, Pipeline $pipeline, Build $build)
    {
        $this->github = $github;
        $this->github->setDefaultConnection('mine');

        $this->operation = $operation;
        $this->server = $server;
        $this->site = $site;
        $this->pipeline = $pipeline;
        $this->build = $build;
    }

    public function showBuild(Request $request) {
        $sites = $this->site->getAllSites();
        $servers = $this->server->getAllServers();
        $pipelines = $this->pipeline->getBuildPipelines();

        return view('build')->with('sites', $sites)->with('servers', $servers)->with('pipelines', $pipelines);
    }

    public function submitBuild(StoreBuildPostRequest $request) {
        $userId = Auth::user()->id;

        foreach($request->input('server_id') as $serverId ) {
            $operationData = [
                'site_id' => $request->input('site_id'),
                'pipeline_id' => $request->input('pipeline_id'),
                'server_id' => (int)$serverId,
                'user_id' => $userId,
                'commit_hash' => $request->input('commit_hash'),
                'version' => $request->input('version'),
                'type' => 'build',
                "dispatch_id" => (string)Uuid::uuid4()
            ];

            $jobId = dispatch(new ProcessBuild($operationData));

            $operationData['job_id'] = $jobId;

            $this->operation->saveOperation($operationData);
        }

        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getCommitsBySiteId(Request $request) {
        $site = new Site;
        $site = $site->getSiteById($request->input('site_id'));

        $commits = $this->github->repo()->tags($site->github_account_name, $site->github_repository_name, array());

        return response()->json([
            'commits' => $commits,
        ]);

    }

    public function testBuild() {
        $buildData =  [
            "site_id" => "1",
            "pipeline_id" => "1",
            "server_id" => 1,
            "user_id" => 1,
            "commit_hash" => "dc810378374bc6860874a565f123bf216ea41572",
            "version" => "v4.0",
            "type" => "build",
            "dispatch_id" => "4643e023-641f-4141-9bff-cccf76cfd158"
        ];

        $buildService = new BuildService($buildData);
        $result = $buildService->build();

        return response()->json([
            'success' => $result,
        ]);
    }

    public function getAllBuilds() {
        return $this->build->getBuildDataTable();
    }

    public function deleteBuildById(Request $request) {
        $build = $this->build->getBuildById($request->segment(3));
        $operation = $this->operation->getOperationByDispatchId($build->dispatch_id);
        $server = $this->server->getServerById($operation->server_id);

        $operationData[] = [
            'build' => $build,
            'server' => $server,
            'operation' => $operation,
            'build_directory' => $operation->build_directory
        ];

        dispatch(new ProcessBuildRemoval($operationData));

        return response()->json([
            'success' => 'true',
        ]);
    }

    public function showTestBuild() {
        return view('test-build');
    }

}