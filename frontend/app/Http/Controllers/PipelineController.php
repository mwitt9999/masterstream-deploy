<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelinePostRequest;
use App\Http\Requests\UpdateTaskListPostRequest;
use Illuminate\Http\Request;
use App\Pipeline;
use App\Task;

class PipelineController extends Controller
{
    protected $pipeline;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function showPipeline(Task $task) {
        $tasks = $task->all();
        $pipelineTasks = $task->getAllPipelinesWithTasks();

        return view('pipelines')->with('tasks', $tasks)->with('pipelineTasks', $pipelineTasks);
    }

    public function addPipeline(StorePipelinePostRequest $request) {
        $this->pipeline->savePipeline($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updatePipeline(StorePipelinePostRequest $request) {
        $this->pipeline->editPipeline($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deletePipeline(Request $request) {
        $this->pipeline->deletePipeline($request->segment(3));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllPipelines() {
        return $this->pipeline->getPipelineDataTable();
    }

    public function updateTaskListForPipelineById(UpdateTaskListPostRequest $request) {
        $this->pipeline->updateTaskListForPipelineById($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllTasksByPipelineId(Request $request, Task $task) {
        $pipelineTasks = $task->getAllTasksByPipelineId($request->segment(4));
        return response()->json([
            'pipelineTasks' => $pipelineTasks
        ]);
    }
}
