<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskPostRequest;
use App\Task;
use App\TaskType;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function showTask() {
        $taskTypes = TaskType::all();
        return view('tasks')->with('taskTypes', $taskTypes);
    }

    public function addTask(StoreTaskPostRequest $request) {
        $this->task->saveTask($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function updateTask(StoreTaskPostRequest $request) {
        $this->task->editTask($request);
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function deleteTask(Request $request) {
        $this->task->deleteTask($request->segment(3));
        return response()->json([
            'success' => 'true',
        ]);
    }

    public function getAllTasks() {
        return $this->task->getTaskDataTable();
    }

}
