<?php

namespace Tests\Unit;

use App\Http\Domain\TaskDomain;
use PHPUnit\Framework\TestCase;

class TaskDomainTest extends TestCase
{
    protected $taskDomain;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskDomain = new TaskDomain();
    }
    public function test_task_domain_output(): void
    {
        $this->assertEquals('1st', $this->taskDomain->ordinal(1));
        $this->assertEquals('2nd', $this->taskDomain->ordinal(2));
        $this->assertEquals('3rd', $this->taskDomain->ordinal(3));
        $this->assertEquals('100th', $this->taskDomain->ordinal(100));
        $this->assertEquals('1012th', $this->taskDomain->ordinal(1012));
    }
}
