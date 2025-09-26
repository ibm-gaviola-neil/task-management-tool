<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Services\TaskService;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getTasks($request);
        return new ApiResponseResource([
            'tasks' => $tasks
        ]);
    }

    /**
     * Get the list of dates for sidebar
     */
    public function storedTasks(){
        $dates = $this->taskService->getTaskHistory();
        return new ApiResponseResource(['dates' => $dates]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $userId = auth()->user()->id;
        $response = $this->taskService->saveTask($request->validated(), $userId);
        return (new ApiResponseResource($response))
            ->response()
            ->setStatusCode($response['status']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Task $task)
    {
        $this->authorize('update', $task);
        $response = $this->taskService->updateTask($task);
        return (new ApiResponseResource($response))
            ->response()
            ->setStatusCode($response['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('viewAny', $task);
        $response = $this->taskService->deleteTask($task);
        return (new ApiResponseResource($response))
            ->response()
            ->setStatusCode($response['status']);
    }
}