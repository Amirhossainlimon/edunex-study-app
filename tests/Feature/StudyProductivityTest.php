<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyProductivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_a_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/tasks', [
            'title' => 'Revise algebra',
            'description' => 'Complete practice problems',
            'status' => 'todo',
            'due_date' => null,
        ])->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Revise algebra',
            'status' => 'todo',
        ]);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $owner->tasks()->create([
            'title' => 'Private task',
            'description' => null,
            'status' => 'todo',
            'due_date' => null,
        ]);

        $this->actingAs($otherUser)->put('/tasks/'.$task->id, [
            'title' => 'Changed without permission',
            'description' => null,
            'status' => 'completed',
            'due_date' => null,
        ])->assertNotFound();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $owner->id,
            'title' => 'Private task',
            'status' => 'todo',
        ]);
    }
}
