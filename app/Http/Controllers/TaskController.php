<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;

class TaskController extends Controller
{

    public function allowed(){
        return auth()->user()->hasAnyRole(['editor','admin']);
    }
    public function index(Request $request)
    {
        try {
   
            $tasks = Task::query();
    
            // Search filter
            if ($request->has('search')) {
                $tasks->search($request->search); 
            }
    
            // Category filter
            if ($request->has('categories')) {
                $selectedCategories = $request->input('categories');
                $tasks->with('categories')
                    ->whereHas('categories', function ($query) use ($selectedCategories) {
                        $query->whereIn('categories.id', $selectedCategories);
                    });
            }
            if($request->has('status')){
                if($request->status=='0'){
                    $tasks->Active();
                }
                else{
                    $tasks->Completed();
                }
            }
    
      
            $todos = $tasks->orderBy('duedate', 'asc')->paginate(10);
            

            $categories = Category::select('id', 'name')->get();
            
            return view('todos.home', compact('todos', 'categories'));
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', $e->getMessage());
        }
    }
    

    public function create()
    {   
        try{
            if($this->allowed()){
                $categories=Category::select('id','name')->get();
                return view('todos.create',compact('categories'));
            }else{
                return redirect(route("todos.index"))->with('error', '403 Forbidden, you are not authorized to access this url');
            }
            
        }catch(\Exception $e){
            return redirect(route("todos.index"))->with('error', '403 Forbidden,you are not authorized to access this url');
        }
      
    }

    public function store(StoreRequest $request)
    {
        try {
            if($this->allowed()){
                Task::createTask($request);
                return redirect(route('todos.index'))->with('success', 'Task created successfully');
            }else{
                return redirect(route('todos.index'))->with('error', '403 Forbidden, you are not authorized to access this url')->withInput();
            }
           
        } catch (\Exception $e) {
            return redirect(route('todos.create'))->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            if($this->allowed()){
                $todo = Task::findOrFail($id);
                return view("todos.update", compact('todo'));
            }else{
                return redirect(route("todos.index"))->with('error', '403 Forbidden, you are not authorized to access this url');
            }
          
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function update($id, Request $request)
    {
        try {
            if($this->allowed()){
                Task::updateTask($id, $request->all());
                return redirect(route("todos.index"))->with('success', 'Updated successfully');
            }else{
                return redirect(route("todos.index"))->with('error', '403 Forbidden, you are not authorized to access this url');
            }
     
        } catch (\Exception $e) {
            return redirect(route("todos.index"))->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        try {
            if($this->allowed()){
                Task::deleteTask($id);
                return redirect(route("todos.index"))->with('success', 'Deleted successfully');
            }else{
                return redirect(route("todos.index"))->with('error', '403 Forbidden, you are not authorized to access this url');
            }
           
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