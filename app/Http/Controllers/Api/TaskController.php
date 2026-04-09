<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /*  Display a listing of the task */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $request->validate([
            'status' => 'nullable|in:pending,in-progress,completed',
            'due_date' => 'nullable|date',
            'page' => 'nullable|integer|min:1',
        ]);

        $user = $request->user();
        $query = $user->isAdmin()
            ? Task::with('user:id,name')->latest()
            : $user->tasks()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->string('due_date'));
        }

        $tasks = $query->paginate(10)->withQueryString();

        return response()->json($tasks);
    }

    /* Store a newly created task in storage */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = $request->user()->tasks()->create($validator->validated())->fresh();

        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    /* Display the specified task */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        return response()->json($task);
    }

    /* Update the specified task in storage */
    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => 'required|date',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    /*  Remove the specified task from storage */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
