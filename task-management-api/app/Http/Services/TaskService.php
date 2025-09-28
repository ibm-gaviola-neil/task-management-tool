<?php

namespace App\Http\Services;

use App\Http\Domain\TaskDomain;
use App\Http\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskService 
{
    private TaskRepositoryInterface $taskRepository;
    public TaskDomain $taskDomain;

    public function __construct(TaskRepositoryInterface $taskRepository, TaskDomain $taskDomain)
    {
        $this->taskRepository = $taskRepository;
        $this->taskDomain = $taskDomain;
    }
    
    public function getTasks(Request $request)
    {
        $date = isset($request->date) ? $request->date : now()->format('Y-m-d');
        $userId = auth()->user()->id;
        return $this->taskRepository->tasks($userId, $date);
    }

    public function saveTask(array $payload, $userId)
    {
        $payload['user_id'] = $userId;
        $lastTask = Task::orderByDesc('order_id')->first();
        $payload['order_id'] = $this->taskDomain->generateOrderId($lastTask);
        $task = $this->taskRepository->store($payload);

        if(!$task)
        {
            return [
                'message' => 'Unable to save task',
                'status' => 500
            ];
        }

        return [
            'message' => 'Task created successfully',
            'status' => 200,
            'task' => $task,
        ];

    }

    public function updateTask(Task $task)
    {
        $update = $this->taskRepository->update($task);

        if(!$update)
        {
            return [
                'message' => 'Unable to update task',
                'status' => 500
            ];
        }

        return [
            'message' => 'Task updated successfully',
            'status' => 200,
            'task' => $task
        ];

    }

    public function deleteTask(Task $task)
    {
        $delete = $this->taskRepository->destroy($task);

        if(!$delete)
        {
            return [
                'message' => 'Unable to delete task',
                'status' => 500
            ];
        }

        return [
            'message' => 'Task deleted successfully',
            'status' => 200,
        ];
    }

    public function getTaskHistory()
    {
        $userId = auth()->user()->id;
        $tasks = $this->taskRepository->tasks($userId);

        $grouped = [];
        $today = Carbon::today();

        foreach ($tasks as $task) {
            $date = Carbon::parse($task->created_at);
            $label = $date->format('l, F j, Y');

            if ($date->isToday()) {
                continue;
            }

            if ($date->isYesterday()) {
                $label = 'Yesterday';
            } elseif ($date->between($today->copy()->subWeek(), $today->copy()->subDay())) {
                $label = 'Last Week';
            } else {
                $label = $this->taskDomain->ordinal($date->weekOfMonth) . ' Week of ' . $date->format('F Y');
            }

            if (!isset($grouped[$label])) {
                $grouped[$label] = [
                    'label'       => $label,
                    'exact_dates' => [],
                ];
            }

            $formatted = [
                'date_desc' => $date->format('l, F j, Y'),
                'date'      => $date->toDateString(),
            ];

            if (!collect($grouped[$label]['exact_dates'])->contains('date', $formatted['date'])) {
                $grouped[$label]['exact_dates'][] = $formatted;
            }
        }

        return array_values($grouped);
    }

    public function updateOrderTask(array $newTasks)
    {
        $isSave = $this->taskRepository->updateOrder($newTasks);

        if(!$isSave){
            return [
                'message' => 'Unable to update task order',
                'status' => 500,
                'tasks' => $isSave,
            ];
        }
        
        return [
            'message' => 'Task order updated successfully',
            'status' => 200,
            'tasks' => $isSave,
        ];
    }
}