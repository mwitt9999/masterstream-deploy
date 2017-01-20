<?php

namespace App;

use Yajra\Datatables\Facades\Datatables;

class Operation extends \Eloquent
{
    protected $fillable = ['created_at', 'commit_hash', 'type', 'version', 'user_id', 'job_id', 'server_id', 'dispatch_id', 'pipeline_id', 'site_id'];

    public function __construct(){}

    public function saveOperation($operationSaveData) {
        $operation = new Operation;
        $operation->server_id =  $operationSaveData['server_id'];
        $operation->user_id =  $operationSaveData['user_id'];
        $operation->commit_hash =  $operationSaveData['commit_hash'];
        $operation->version =  $operationSaveData['version'];
        $operation->build_directory =  '';
        $operation->type =  $operationSaveData['type'];
        $operation->job_id =  $operationSaveData['job_id'];
        $operation->dispatch_id =  $operationSaveData['dispatch_id'];
        $operation->site_id =  $operationSaveData['site_id'];
        $operation->pipeline_id =  $operationSaveData['pipeline_id'];
        $operation->status =  'Pending';
        $operation->save();
    }

    public function editOperationByDispatchId($dispatch_id, $updated_fields) {
        self::where('dispatch_id', $dispatch_id)->update($updated_fields);
    }

    public function getAllOperations() {
        return self::all();
    }

    public function failedJobs() {
        return $this->belongsTo('App\FailedJobs', 'job_id', 'id');
    }

    public function server(){
        return $this->belongsTo('App\Server', 'server_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getOperationHistoryDataTable() {
        $operations = self::with('server', 'user')->get();
        return Datatables::of($operations)->make(true);
    }

    public function getOperationByDispatchId($dispatchId) {
        return self::where('dispatch_id', $dispatchId)->first();
    }
}
