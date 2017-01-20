<?php

namespace App;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class Server extends \Eloquent
{
    protected $fillable = ['created_at', 'updated_at', 'name', 'ip'];

    public function __construct()
    {
    }

    public function deleteServer($serverId) {
        self::destroy($serverId);
    }

    public function editServer(Request $request) {
        $data = ['name' => $request->input('name'), 'ip' => $request->input('ip')];
        self::find($request->input('id'))->update($data);
    }

    public function saveServer(Request $request) {
        $server = new Server;
        $server->name = $request->input('name');
        $server->ip = $request->input('ip');
        $server->save();
    }

    public function getAllServers() {
        return self::all();
    }

    public function getServerById($id) {
        return self::find($id);
    }

    public function getServerDataTable() {
        $servers = self::all();
        return Datatables::of($servers)->make(true);
    }

}
