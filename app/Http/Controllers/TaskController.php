<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TodoList;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //search
    public function index(Request $request)
{
    $query = Task::whereHas('todoList', function ($q) use ($request) {
        $q->where('user_id', $request->user()->id);
    });


    if ($request->has('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }


    if ($request->has('list')) {
        $query->whereHas('todoList', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->list . '%');
        });
    }

    $tasks = $query->with('todoList')->get();

    return response()->json([
        'data' => $tasks,
        'message' => null,
        'errors' => null
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {

        $inputs = $request->validate([
            'todo_list_id' => ['required', 'exists:todo_lists,id'],
            'title'        => ['required', 'string', 'max:255'],
            'due_date'     => ['nullable', 'date'],
            'priority'     => ['sometimes', 'in:Low,Medium,High'],
            'is_completed' => ['sometimes', 'boolean'],
        ]);


        $todoList = TodoList::where('id', $inputs['todo_list_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $task = $todoList->tasks()->create($inputs);

        return response()->json([
            'data' => $task,
            'message' => 'Task created successfully',
            'errors' => null
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'title'        => ['sometimes', 'string', 'max:255'],
            'priority'     => ['sometimes', 'in:Low,Medium,High'],
            'due_date'     => ['nullable', 'date'],
            'is_completed' => ['boolean'],
        ]);

        $task = Task::where('id', $id)
            ->whereHas('todoList', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        $task->update($inputs);

        return response()->json([
            'data' => $task,
            'message' => 'Task updated successfully',
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->whereHas('todoList', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        $task->delete();

        return response()->json([
            'data' => null,
            'message' => 'Task deleted',
            'errors' => null
        ]);
    }


    public function completed(Request $request)
    {
        $tasks = Task::where('is_completed', true)
            ->whereHas('todoList', fn ($q) =>
                $q->where('user_id', $request->user()->id)
            )
            ->get();

        return response()->json([
            'data' => $tasks,
            'message' => null,
            'errors' => null
        ]);
    }


    public function upcoming(Request $request)
    {
        $tasks = Task::whereDate('due_date', '>=', now()->toDateString())
            ->whereHas('todoList', fn ($q) =>
                $q->where('user_id', $request->user()->id)
            )
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'data' => $tasks,
            'message' => null,
            'errors' => null
        ]);
    }
}
