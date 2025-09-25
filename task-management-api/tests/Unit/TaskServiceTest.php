<?php

namespace Tests\Unit;

use App\Http\Domain\TaskDomain;
use App\Http\Repositories\Interfaces\TaskRepositoryInterface;
use App\Http\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Http\Services\TaskService;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_tasks_with_date()
    {
        $date = '2025-09-24';
        $request = new Request(['date' => $date]);

        $expectedTasks = new EloquentCollection([
           ['id' => 1, 'created_at' => $date],
           ['id' => 2, 'created_at' => $date],
        ]);

        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('tasks')
            ->with($date)
            ->andReturn($expectedTasks);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        $actualTasks = $service->getTasks($request);

        $this->assertEquals($expectedTasks, $actualTasks);
    }

    public function test_get_tasks_defaults_to_today_when_no_date_provided()
    {
        $request = new Request();

        $today = now()->format('Y-m-d');

        $expectedTasks = new EloquentCollection([
            ['id' => 1, 'created_at' => $today],
            ['id' => 2, 'created_at' => $today],
        ]);

        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('tasks')
            ->with($today)
            ->andReturn($expectedTasks);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        $actualTasks = $service->getTasks($request);

        $this->assertEquals($expectedTasks, $actualTasks);
    }

    public function test_save_task_successful()
    {
        // Arrange
        $payload = [
            'title' => 'Test Task',
            // user_id will be added by the service
        ];
        $userId = 123;

        // Simulate the current user
        $mockUser = (object)['id' => $userId];

        // Mock the request global helper
        \Illuminate\Support\Facades\Facade::clearResolvedInstance('request');
        $mockRequest = Mockery::mock('alias:request');
        $mockRequest->shouldReceive('user')->andReturn($mockUser);

        // Expected payload sent to repository
        $expectedPayload = $payload;
        $expectedPayload['user_id'] = $userId;

        // Expected repository return
        $task = [
            'id' => 1,
            'title' => 'Test Task',
            'user_id' => $userId
        ];

        $expectedTask = new Task($task);

        // Mock repository
        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('store')
            ->with($expectedPayload)
            ->andReturn($expectedTask);

        // Mock domain (not used in this method)
        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        // Act
        $result = $service->saveTask($payload, $userId);

        // Assert
        $this->assertEquals([
            'message' => 'Task created successfully',
            'status' => 200,
            'task' => $expectedTask,
        ], $result);
    }

    public function test_save_task_failure()
    {
        $payload = [
            'title' => 'Test Task',
        ];
        $userId = 123;

        $mockUser = (object)['id' => $userId];

        \Illuminate\Support\Facades\Facade::clearResolvedInstance('request');
        $mockRequest = Mockery::mock('alias:request');
        $mockRequest->shouldReceive('user')->andReturn($mockUser);

        $expectedPayload = $payload;
        $expectedPayload['user_id'] = $userId;

        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('store')
            ->with($expectedPayload)
            ->andReturn(null);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        $result = $service->saveTask($payload, $userId);

        $this->assertEquals([
            'message' => 'Unable to save task',
            'status' => 500
        ], $result);
    }

    public function test_update_task_successful()
    {
        // Arrange
        $task = new Task([
            'id' => 1,
            'title' => 'Original Title'
        ]);

        $mockRepository = \Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('update')
            ->with($task)
            ->andReturn(true);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        // Act
        $result = $service->updateTask($task);

        // Assert
        $this->assertEquals([
            'message' => 'Task updated successfully',
            'status' => 200,
            'task' => $task
        ], $result);
    }

    public function test_update_task_failure()
    {
        // Arrange
        $task = new Task([
            'id' => 1,
            'title' => 'Original Title'
        ]);

        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('update')
            ->with($task)
            ->andReturn(false);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        // Act
        $result = $service->updateTask($task);

        // Assert
        $this->assertEquals([
            'message' => 'Unable to update task',
            'status' => 500
        ], $result);
    }

    public function test_delete_task_successful()
    {
        // Arrange
        $task = new Task([
            'id' => 1,
            'title' => 'Sample Task'
        ]);

        $mockRepository = Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('destroy')
            ->with($task)
            ->andReturn(true);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        // Act
        $result = $service->deleteTask($task);

        // Assert
        $this->assertEquals([
            'message' => 'Task deleted successfully',
            'status' => 200,
        ], $result);
    }

    public function test_delete_task_failure()
    {
        // Arrange
        $task = new Task([
            'id' => 1,
            'title' => 'Sample Task'
        ]);

        $mockRepository = \Mockery::mock(TaskRepositoryInterface::class);
        $mockRepository->shouldReceive('destroy')
            ->with($task)
            ->andReturn(false);

        $mockDomain = Mockery::mock(TaskDomain::class);

        $service = new TaskService($mockRepository, $mockDomain);

        // Act
        $result = $service->deleteTask($task);

        // Assert
        $this->assertEquals([
            'message' => 'Unable to delete task',
            'status' => 500,
        ], $result);
    }

    public function test_get_task_history_groups_tasks_by_label()
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();
        $lastWeekDay = $today->copy()->subDays(3);
        $otherWeekDay = $today->copy()->subDays(10);

        $user = User::factory()->create([
            'id' => 1,
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);
        $userId = $user->id;
        // Persist tasks in the DB
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task', 'created_at' => $today->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task', 'created_at' => $yesterday->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task', 'created_at' => $lastWeekDay->toDateString().' 00:00:00']);
        Task::factory()->create(['user_id' => $userId, 'task_description' => 'test task', 'created_at' => $otherWeekDay->toDateString().' 00:00:00']);

        $mockDomain = Mockery::mock(TaskDomain::class);
        $mockDomain->shouldReceive('ordinal')->with(2)->andReturn('2nd');

        $taskService = new TaskService(new TaskRepository(), $mockDomain);

        $result = $taskService->getTaskHistory();
        $this->assertCount(3, $result);
        $labels = array_column($result, 'label');
        $this->assertContains('Yesterday', $labels);
        $this->assertContains('Last Week', $labels);
        $this->assertContains('2nd Week of ' . $otherWeekDay->format('F Y'), $labels);

        foreach ($result as $group) {
            foreach ($group['exact_dates'] as $dateEntry) {
                $this->assertArrayHasKey('date_desc', $dateEntry);
                $this->assertArrayHasKey('date', $dateEntry);
            }
        }
    }
}
