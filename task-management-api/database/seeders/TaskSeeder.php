<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [];
        $count = 0;
        $user_id = User::first()?->id ?? User::factory()->create()->id;

        // Today (3 tasks)
        for ($i = 0; $i < 3; $i++) {
            $count++;
            $tasks[] = [
                'task_description' => fake()->sentence(),
                'user_id' => $user_id,
                'order_id' => $count,
                'status' => 0,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
            ];
        }

        // Yesterday (3 tasks)
        for ($i = 0; $i < 3; $i++) {
            $count++;
            $tasks[] = [
                'task_description' => fake()->sentence(),
                'user_id' => $user_id,
                'order_id' => $count,
                'status' => 0,
                'created_at' => Carbon::yesterday(),
                'updated_at' => Carbon::yesterday(),
            ];
        }

        // Last week (4 tasks spread over last 7 days)
        for ($i = 0; $i < 4; $i++) {
            $count++;
            $date = Carbon::today()->subDays(rand(2, 7));
            $tasks[] = [
                'task_description' => fake()->sentence(),
                'user_id' => $user_id,
                'order_id' => $count,
                'status' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Random past months (5 tasks)
        for ($i = 0; $i < 5; $i++) {
            $count++;
            $date = Carbon::today()->subMonths(rand(1, 6))->subDays(rand(1, 28));
            $tasks[] = [
                'task_description' => fake()->sentence(),
                'user_id' => $user_id,
                'order_id' => $count,
                'status' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        Task::insert($tasks);
    }
}
