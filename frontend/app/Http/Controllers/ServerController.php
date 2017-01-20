<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServerPostRequest;
use App\Pipeline;
use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    protected $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function showServers() {
        $pipeline = new Pipeline;
        $pipelines = $pipeline->getAllPipelines();

        return view('servers')->with('pipelines', $pipelines);
    }

    public function addServer(StoreServerPostRequest $request) {
        $this->server->saveServer($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updateServer(StoreServerPostRequest $request) {
        $this->server->editServer($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deleteServer(Request $request) {
        $this->server->deleteServer($request->segment(3));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllServers() {
        return $this->server->getServerDataTable();
    }

}
