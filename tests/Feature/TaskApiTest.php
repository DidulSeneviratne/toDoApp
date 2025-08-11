<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Task;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_example_command_runs()
    {
        $this->artisan('inspire')
            ->assertExitCode(0);
    }

    public function test_inspire_command_outputs_text()
    {
        $this->artisan('inspire')
            ->expectsOutputToContain('—') // it outputs a quote
            ->assertExitCode(0);
    }

    public function test_get_tasks_when_empty()
    {
        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function test_create_task_with_invalid_completed_field()
    {
        $this->postJson('/api/tasks', [
            'title' => 'Test',
            'completed' => 'not_boolean'
        ])->assertStatus(422);
    }

    public function test_update_non_existent_task()
    {
        $this->putJson('/api/tasks/999', [
            'title' => 'No Task',
            'completed' => true
        ])->assertStatus(404);
    }

    public function test_can_create_task()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task'
        ]);

        $response->assertStatus(201)
                 ->assertJson(['title' => 'Test Task', 'completed' => false]);

        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
        $this->postJson('/api/tasks', [])->assertStatus(422);
    }

    public function test_can_get_tasks()
    {
        Task::create(['title' => 'Test Task']);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_can_update_task()
    {
        $task = Task::create(['title' => 'Old Title']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'completed' => true
        ]);

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Title', 'completed' => true]);

        $this->assertDatabaseHas('tasks', ['title' => 'Updated Title']);
        $this->putJson('/api/tasks/999', ['title' => 'X'])->assertStatus(404);
    }

    public function test_can_delete_task()
    {
        $task = Task::create(['title' => 'To Be Deleted']);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['title' => 'To Be Deleted']);
        $this->putJson('/api/tasks/999', ['title' => 'X'])->assertStatus(404);
    }
}
