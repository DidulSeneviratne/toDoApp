<?php

namespace Tests\Unit;

use App\Models\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function test_task_can_be_instantiated()
    {
        $task = new Task([
            'title' => 'Example Task',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Example Task', $task->title);
    }

    public function test_default_completed_status_is_false()
    {
        $task = new Task(['title' => 'Sample']);
        $this->assertFalse((bool) $task->completed);
    }

    public function test_task_fillable_fields()
    {
        $task = new Task([
            'title' => 'Test Fillable',
            'completed' => true,
            'invalid_field' => 'should be ignored'
        ]);

        $this->assertEquals('Test Fillable', $task->title);
        $this->assertTrue((bool) $task->completed);
        $this->assertFalse(property_exists($task, 'invalid_field'));
    }

    public function test_task_has_correct_fillable_fields()
    {
        $task = new \App\Models\Task();
        $this->assertEquals(['title', 'completed'], $task->getFillable());
    }

    public function test_service_provider_registers()
    {
        $provider = new \App\Providers\AppServiceProvider(app());
        $this->assertNull($provider->register());
        $this->assertNull($provider->boot());
    }

    public function test_app_service_provider_is_loaded()
    {
        $provider = new \App\Providers\AppServiceProvider(app());
        $this->assertInstanceOf(\Illuminate\Support\ServiceProvider::class, $provider);
    }

    public function test_task_table_name()
    {
        $task = new Task();
        $this->assertEquals('tasks', $task->getTable());
    }
}
