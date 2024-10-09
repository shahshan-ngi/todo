<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;

class TaskController extends Controller
{
    public function index(Request $request){
        $tasks=Task::query();
        if($request->has('search')){
            $tasks->where('title','like','%'.$request->search.'%')->orwhere('description','like','%'.$request->search.'%');
        }
        if($request->has('status')){
            $tasks->where('status',$request->status);
        }
        $todos=$tasks->orderBy('duedate','asc')->paginate(10);
       
      
        return view('home',compact('todos'));
    }
    public function create(){
        return view('create');
    }

    public function store(StoreRequest $request){
        try{
            Task::create($request->all());
            return redirect(route('todo.home'));
        }catch(\Exception $e){
            return redirect(route('todo.create'));
        }


    }

    public function edit($id){
        try{
            $todo=Task::findorfail($id);
            return view("update",compact('todo'));
        }catch(\Exception $e){

        }
       
    }

    public function update(StoreRequest $request){
        try{
           
            $id=$request->id;
            $task=Task::findorfail($id);
            
            $task->update($request->all());
            return redirect(route("todo.home"));
        }catch (\Exception $e){

        }
    }

    public function delete($id){
        try{
            $task=Task::findorfail($id);
            $task->delete();
            return redirect(route("todo.home"));
        }catch(\Exception $e){

        }
    }
    public function updateStatus($id)
    {
        $todo = Task::find($id);
        $todo->status = !$todo->status;
        $todo->save();

        return redirect(route("todo.home"));
    }
}
