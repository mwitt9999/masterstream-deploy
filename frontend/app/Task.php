<?php

namespace App;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class Task extends \Eloquent
{
    protected $fillable = ['name', 'command', 'output_message', 'run_from_build_directory', 'command_directory'];

    public function __construct()
    {
    }

    public function deleteTask($taskId) {
        self::destroy($taskId);
    }

    public function editTask(Request $request) {
        $data = [
            'name' => $request->input('name'),
            'command' => $request->input('command'),
            'output_message' => $request->input('output_message'),
            'run_from_build_directory' => $request->input('run_from_build_directory'),
            'command_directory' => $request->input('command_directory'),
        ];

        self::find($request->input('id'))->update($data);
    }

    public function saveTask(Request $request) {
        $task = new Task;
        $task->name = $request->input('name');
        $task->command = $request->input('command');
        $task->output_message = $request->input('output_message');
        $task->run_from_build_directory = $request->input('run_from_build_directory');
        $task->command_directory = $request->input('command_directory');
        $task->save();
    }

    public function getAllTasks() {
        return self::all();
    }

    public function getTaskById($id) {
        return self::find($id);
    }

    public function getTaskDataTable() {
        $tasks = self::all();

        return Datatables::of($tasks)->make(true);
    }

    public function pipelineTasks() {
        return $this->hasMany('App\PipelineTasks', 'pipeline_id', 'id');
    }

    public function getAllTasksByPipelineId($id) {
        return DB::table('pipelines')
            ->join('pipelines_tasks', 'pipelines_tasks.pipeline_id', '=', 'pipelines.id')
            ->join('tasks', 'pipelines_tasks.task_id', '=', 'tasks.id')
            ->where('pipelines.id', $id)
            ->get();
    }

    public function getAllPipelinesWithTasks()
    {
        return DB::table('pipelines')
            ->join('pipelines_tasks', 'pipelines_tasks.pipeline_id', '=', 'pipelines.id')
            ->join('tasks', 'pipelines_tasks.task_id', '=', 'tasks.id')->get();
    }
}
