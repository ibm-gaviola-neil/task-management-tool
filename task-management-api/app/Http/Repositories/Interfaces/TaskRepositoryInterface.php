<?php

namespace App\Http\Repositories\Interfaces;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface TaskRepositoryInterface
{
    public function tasks($user, ?string $date = null) : EloquentCollection;
    public function store(array $payload) : ?Task;
    public function update(Task $task) : bool;
    public function destroy(Task $task) : bool;
}