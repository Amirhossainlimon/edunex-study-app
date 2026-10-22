<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = $request->user()->tasks()
            ->orderByRaw("CASE status WHEN 'todo' THEN 0 WHEN 'in_progress' THEN 1 ELSE 2 END")
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->paginate(12)
            ->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create', ['task' => new Task()]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $task = auth()->user()->tasks()->findOrFail($task->getKey());
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $ownedTask = $request->user()->tasks()->findOrFail($task->getKey());
        $ownedTask->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $request->user()->tasks()->findOrFail($task->getKey())->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function toggle(Request $request, Task $task): RedirectResponse
    {
        $ownedTask = $request->user()->tasks()->findOrFail($task->getKey());
        $ownedTask->update([
            'status' => $ownedTask->status === 'completed' ? 'todo' : 'completed',
        ]);

        return back()->with('success', $ownedTask->status === 'completed' ? 'Task marked complete.' : 'Task reopened.');
    }
}
