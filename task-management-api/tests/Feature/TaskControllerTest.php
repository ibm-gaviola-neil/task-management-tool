<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_unauthenticated_return_401(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    public function test_get_tasks_without_date(): void
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);

        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $userId = $user->id;

        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'status' => 0, 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2', 'status' => 0, 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3', 'status' => 0, 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 4', 'status' => 0, 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);
        
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'task_description' => 'test task 1',
            'user_id' => $userId,
            'status' => 0,
        ]);
        
        $responseDate = now()->format('Y-m-d H:i:s');
        $response->assertJsonFragment(['response_date' => $responseDate]);
    }

    public function test_get_tasks_with_date(): void
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);

        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $userId = $user->id;

        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'status' => 0, 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2', 'status' => 0, 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3', 'status' => 0, 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 4', 'status' => 0, 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);
        
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/tasks?date='.$yesterday->toDateString());

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'task_description' => 'test task 2',
            'user_id' => $userId,
            'status' => 0,
        ]);
        
        $responseDate = now()->format('Y-m-d H:i:s');
        $response->assertJsonFragment(['response_date' => $responseDate]);
    }

    public function test_returns_grouped_task_history_by_label()
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);

        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $userId = $user->id;

        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'status' => 0, 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2', 'status' => 0, 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3', 'status' => 0, 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 4', 'status' => 0, 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/tasks/sidebar-dates');

        $response->assertOk();
        $response->assertJsonStructure([
            'dates' => [
                '*' => [
                    'label',
                    'exact_dates' => [
                        '*' => ['date_desc', 'date']
                    ]
                ]
            ]
        ]);

        $labels = collect($response['dates'])->pluck('label');

        $this->assertFalse($labels->contains($today->format('l, F j, Y')));

        $this->assertTrue($labels->contains('Yesterday'));

        $this->assertTrue($labels->contains('Last Week') || $labels->contains(function ($label) {
            return str_contains($label, 'Week of');
        }));

        $datesReturned = collect($response['dates'])->flatMap(function ($group) {
            return collect($group['exact_dates'])->pluck('date');
        });

        $this->assertTrue($datesReturned->contains($yesterday->toDateString()));
        $this->assertTrue($datesReturned->contains($lastWeekDay->toDateString()));
        $this->assertTrue($datesReturned->contains($otherWeekDay->toDateString()));
        $this->assertFalse($datesReturned->contains($today->toDateString()));
    }

    public function test_authenticated_user_can_store_a_task()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);
        $userId = $user->id;

        $taskData = [
            'task_description' => 'This is a test task.',
            'user_id' => $userId,
        ];

        $this->actingAs($user);

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(200); 
        $response->assertJson([
            'message' => 'Task created successfully',
            'status' => 200,
        ]);

        $this->assertDatabaseHas('tasks', [
            'task_description' => 'This is a test task.',
            'user_id' => $userId,
        ]);
    }

    public function test_task_required_validation()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $taskData = [
            'task_description' => '',
        ];

        $this->actingAs($user);

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(422); 
        $response->assertJson([
            'errors' => [
                'task_description' => ['Please provide task description.'],
            ]
        ]);
    }

    public function test_task_max_validation()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 280; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $taskData = [
            'task_description' => $randomString,
        ];

        $this->actingAs($user);

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(422); 
        $response->assertJson([
            'errors' => [
                'task_description' => ['Task only 255 maximu charater.'],
            ]
        ]);
    }

    public function test_task_update_with_0_status()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

       $task = Task::factory()->create(['user_id' => $user->id, 'task_description' => 'test task 1', 'status' => 0, 'created_at' => now()]);

        $this->actingAs($user);

        $response = $this->putJson('/api/tasks/' . $task->id);

        $response->assertStatus(200); 
        $response->assertJson([
            'message' => 'Task updated successfully',
            'status' => 200,
            'task' => [
                'id' => $task->id,
                'task_description' => 'test task 1',
                'status' => 1,
                'user_id' => $user->id,
            ]
        ]);
    }

    public function test_task_update_with_1_status()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

       $task = Task::factory()->create(['user_id' => $user->id, 'task_description' => 'test task 1', 'status' => 1, 'created_at' => now()]);

        $this->actingAs($user);

        $response = $this->putJson('/api/tasks/' . $task->id);

        $response->assertStatus(200); 
        $response->assertJson([
            'message' => 'Task updated successfully',
            'status' => 200,
            'task' => [
                'id' => $task->id,
                'task_description' => 'test task 1',
                'status' => 0,
                'user_id' => $user->id,
            ]
        ]);
    }

    public function test_task_delete_function()
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);

       $task = Task::factory()->create(['user_id' => $user->id, 'task_description' => 'test task 1', 'status' => 1, 'created_at' => now()]);

        $this->actingAs($user);

        $response = $this->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(200); 
        $response->assertJson([
            'message' => 'Task deleted successfully',
            'status' => 200,
        ]);
    }
}
