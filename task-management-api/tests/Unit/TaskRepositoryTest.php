<?php

namespace Tests\Unit;

use App\Http\Repositories\TaskRepository;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $tasRepository;

    public function setUp() : void 
    {
        parent::setUp();
        $this->tasRepository = new TaskRepository();
    }

    public function test_get_task_with_blank_date(): void
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);
        $date = '';

        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);
        $userId = $user->id;

        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2', 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3', 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 4', 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);

        $tasks = $this->tasRepository->tasks($userId, $date);
        $this->assertCount(4, $tasks);
        foreach ($tasks as $key => $task) {
            $this->assertEquals($userId, $task->user_id);
            $this->assertEquals('test task ' .$key +1, $task->task_description);
        }
    }

    public function test_get_task_with_date(): void
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);
        $date = now()->format('Y-m-d');

        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);
        $userId = $user->id;

        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2', 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3', 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 4', 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);

        $tasks = $this->tasRepository->tasks($userId, $date);
        $this->assertCount(1, $tasks);
        foreach ($tasks as $key => $task) {
            $this->assertEquals($userId, $task->user_id);
            $this->assertEquals('test task ' .$key +1, $task->task_description);
        }
    }

    public function test_store_task_success()
    {
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);

        $userId = $user->id;

        $payload = [
            'task_description' => 'test task 1',
            'user_id' => $userId,
            'status' => 0
        ];

        $task = $this->tasRepository->store($payload);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('test task 1', $task->task_description);
        $this->assertEquals($userId, $task->user_id);
    }

    public function test_update_status_with_0(){
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);

        $userId = $user->id;

        $task = Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1']);

        $isUpdate = $this->tasRepository->update($task);

        $this->assertTrue($isUpdate);
        $this->assertEquals(1, $task->status);
    }

    public function test_update_status_with_1(){
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);

        $userId = $user->id;

        $task = Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1', 'status' => 1]);

        $isUpdate = $this->tasRepository->update($task);

        $this->assertTrue($isUpdate);
        $this->assertEquals(0, $task->status);
    }

    public function test_delete_task(){
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password')
        ]);

        $userId = $user->id;

        $task = Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 1']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 2']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task 3']);

        $isDeleted = $this->tasRepository->destroy($task);
        $tasks = $this->tasRepository->tasks($userId);

        $this->assertCount(2, $tasks);
        $this->assertTrue($isDeleted);
    }
}
