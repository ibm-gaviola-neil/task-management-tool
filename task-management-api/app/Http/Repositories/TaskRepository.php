<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Models\Task;
use App\Models\User;

class TaskRepository implements TaskRepositoryInterface
{
    public function tasks($user, ?string $date = '') : EloquentCollection
    {
        $query = Task::query();

        if(!blank($date)){
            $query->whereDate('created_at', $date);
        }

        return $query->where('user_id', $user)->orderBy('order_id', 'ASC')->get();
    }

    public function store(array $payload) : Task
    {
        return Task::create($payload);
    }

    public function update(Task $task) : bool
    {
        $task->status = $task->status === 1 ? 0 : 1;
        return $task->save();
    }

    public function updateOrder(array $tasks) : bool
    {
        foreach($tasks as $key => $taskData){
            $task = Task::where('id', $taskData['id'])->first();
            if($task){
                $task->order_id = $key + 1;
                $heyyy[] = $task;
                $task->update();
            }else{
                $heyyy[] = $taskData;
            }
        }
        return true;
    }

    public function destroy(Task $task) : bool
    {
        return $task->delete();
    }
}