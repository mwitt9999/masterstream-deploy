<?php

namespace App;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class Pipeline extends \Eloquent
{
    protected $fillable = ['name', 'type'];
    protected $task;

    public function __construct()
    {
        $this->task = new Task;
    }

    public function deletePipeline($pipelineId) {
        self::destroy($pipelineId);
    }

    public function editPipeline(Request $request) {
        $data = ['name' => $request->input('name'), 'type' => $request->input('type')];
        self::find($request->input('id'))->update($data);
    }

    public function savePipeline(Request $request) {
        $pipeline = new Pipeline;
        $pipeline->name = $request->input('name');
        $pipeline->type = $request->input('type');
        $pipeline->save();
    }

    public function getAllPipelines() {
        return self::all();
    }

    public function getPipelineById($id) {
        return self::find($id);
    }

    public function getPipelineDataTable() {
        return Datatables::of($this->getAllPipelines())->make(true);
    }

    public function updateTaskListForPipelineById(Request $request) {
        PipelinesTasks::deleteAllPipelinesTasksByPipelineId($request->input('pipeline_id'));

        foreach($request->input('task_ids') as $position => $taskId) {
            $pipelinesTasks = new PipelinesTasks;
            $pipelinesTasks->pipeline_id = (int)$request->input('pipeline_id');
            $pipelinesTasks->task_id = (int)$taskId;
            $pipelinesTasks->position = (int)$position;

            $pipelinesTasks->save();
        }
    }

    public function getBuildPipelines() {
        return self::where('type', 'Build')->get();
    }

    public function getTasksByPipelineId($id) {
        $task = new Task;
        return $task->getAllTasksByPipelineId($id);
    }
}
