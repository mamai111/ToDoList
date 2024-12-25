<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $TodoList = TodoList::whereUserId(auth()->user()->id)->get();
        return Inertia::render('TodoList', ['todos'=>$TodoList]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255|not_regex:/<script.*?>.*?<\/script>/i',
        ]);

        // Optionally sanitize further
        $validatedData['content'] = strip_tags($validatedData['content']); // Remove any remaining HTML tags

        $validatedData['user_id'] = auth()->id();
        $validatedData['is_done'] = false;

        TodoList::create($validatedData);

        return redirect('todos')->with('success', 'Task added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(TodoList $todoList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TodoList $TodoList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TodoList $TodoList)
    {
        $TodoList->is_done = !$TodoList->is_done;
        $TodoList->save();
        return redirect('todos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $TodoList)
    {
        $TodoList->delete();
        return redirect('todos');
    }
}
