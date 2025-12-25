<?php

namespace App\Http\Controllers;
use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $lists = TodoList::with('tasks')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'data' => $lists,
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
            'name' => ['required', 'string', 'max:255'],
        ]);

        return TodoList::create([
            'name'    => $inputs['name'],
            'user_id' => $request->user()->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
   public function show(Request $request, $id)
    {
        $list = TodoList::with('tasks')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json([
            'data' => $list,
            'message' => null,
            'errors' => null
        ]);
    }
 /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $list = TodoList::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $list->update($inputs);

        return $list;
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy(Request $request, $id)
    {
        $list = TodoList::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $list->delete();

        return response()->json([
            'message' => 'Todo list deleted'
        ]);
    }
}
