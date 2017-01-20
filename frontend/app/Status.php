<?php

namespace App;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class Status extends \Eloquent
{
    protected $fillable = ['server_id', 'deployment_id', 'job_id', 'status', 'exception'];
    protected $table = 'status';

    public function __construct(){}

    public function deleteStatus($statusId) {
        self::destroy($statusId);
    }

    public function editStatus(Request $request) {
        $data = ['name' => $request->input('name'), 'ip' => $request->input('ip')];
        self::find($request->input('id'))->update($data);
    }

    public function saveStatus(Request $request) {
        $status = new Status;
        $status->name = $request->input('name');
        $status->ip = $request->input('ip');
        $status->save();
    }

    public function getAllStatus() {
        return self::all();
    }

    public function getStatusDataTable() {
        return Datatables::of($this->getAllStatus())->make(true);
    }

}
