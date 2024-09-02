<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        // dd($request->task);
        $existingTask = Todo::where('task', $request->task)->first();

        if ($existingTask) {
            return response()->json(['message' => 'Task already exists'], 409);
        }

        $todo = Todo::create([
            'task' => $request->task,
            'completed' => false
        ]);

        return response()->json($todo, 201);
    }

    public function fetch($status)
    {
        if ($status === 'all') {
            $todos = Todo::all();
        } else {
            $todos = Todo::where('completed', $status === 'completed')->get();
        }

        return response()->json($todos, 200);
    }

    public function statusUpdate($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $todo->completed = true;
        $todo->save();

        return response()->json(['message' => 'Task marked as completed'], 200);
    }

    public function delete($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $todo->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
