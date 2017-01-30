<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Build extends Model
{
    protected $fillable = ['dispatch_id', 'on_server'];

    public function saveBuild($dispatchId, $on_server) {
        $build = new Build;
        $build->dispatch_id = $dispatchId;
        $build->on_server = $on_server;

        $build->save();

        return $build->id;
    }

    public function editBuildById($id) {
        $data = [
            'on_server' => 0,
        ];

        self::find($id)->update($data);
    }

    public function getBuildById($id) {
        return self::find($id);
    }

    public function getBuildDataTable() {
        $builds = DB::table('builds')
            ->join('operations', 'operations.dispatch_id', '=', 'builds.dispatch_id')
            ->join('sites', 'operations.site_id', '=', 'sites.id')
            ->join('servers', 'operations.server_id', '=', 'servers.id')
            ->select('builds.*', 'operations.build_directory', 'operations.commit_hash', 'operations.version', 'sites.name as site_name', 'servers.name as server_name')
            ->where('builds.on_server', '=', 1)
            ->get();

        return Datatables::of($builds)->make(true);
    }
}
