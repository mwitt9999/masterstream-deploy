<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSitePostRequest;
use App\Server;
use App\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    protected $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function showSites() {
        $server = new Server;
        $servers = $server->getAllServers();

        return view('sites')->with('servers', $servers);
    }

    public function addSite(StoreSitePostRequest $request) {
        $this->site->saveSite($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updateSite(StoreSitePostRequest $request) {
        $this->site->editSite($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deleteSite(Request $request) {
        $this->site->deleteSite($request->segment(3));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllSites() {
        return $this->site->getSiteDataTable();
    }

}
