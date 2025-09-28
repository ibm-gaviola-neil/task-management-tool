<?php

namespace App\Http\Domain;

use App\Models\Task;

class TaskDomain {
    public function ordinal(int $number): string
    {
        $suffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        }
        return $number . $suffixes[$number % 10];
    }

    public function generateOrderId(?Task $task) : int
    {
        $taskLastOrderId = 0;

        if(isset($task) && isset($task->order_id))
        {
            $taskLastOrderId = (int) $task->order_id;
        }

        return $taskLastOrderId + 1;
    }
}