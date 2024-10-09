<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = Task::query();

        
            if ($request->has('search')) {
                $tasks = Task::search($request->search);
            }

  
            $todos = $tasks->orderBy('duedate', 'asc')->paginate(10);
            return view('todos.home', compact('todos'));
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function create()
    {   
        try{
            return view('todos.create');
        }catch(\Exception $e){
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
      
    }

    public function store(StoreRequest $request)
    {
        try {
            Task::createTask($request->all());
            return redirect(route('todos.index'))->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            return redirect(route('todos.create'))->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        try {
            $todo = Task::findOrFail($id);
            return view("todos.update", compact('todo'));
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function update($id, Request $request)
    {
        try {
            Task::updateTask($id, $request->all());
            return redirect(route("todos.index"))->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        try {
            Task::deleteTask($id);
            return redirect(route("todos.index"))->with('success', 'Deleted successfully');
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function updateStatus($id)
    {   
        try {
            $todo = Task::find($id);
            $todo->status = !$todo->status;
            $todo->save();
            return redirect(route("todos.index"))->with('success', 'Status changed successfully');
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }
}