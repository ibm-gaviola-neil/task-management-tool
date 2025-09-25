<?php

namespace App\Http\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface TaskRepositoryInterface
{
    public function tasks(?string $date = null) : EloquentCollection;
    public function store(array $payload) : ?Task;
    public function update(Task $task) : bool;
    public function destroy(Task $task) : bool;
}