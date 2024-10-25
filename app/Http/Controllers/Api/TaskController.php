<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoriesCollection;

class TaskController extends Controller
{
    public function allowed(){
        return auth()->user()->hasAnyRole(['editor','admin']);
    }
  
    
    public function index(Request $request)
    {
        try {
            $tasks = Task::query();
            if ($request->has('search')) {
                $tasks->search($request->search); 
            }
            if ($request->has('categories')) {
                $selectedCategories = $request->input('categories');
                $tasks->with('categories')
                    ->whereHas('categories', function ($query) use ($selectedCategories) {
                        $query->whereIn('categories.id', $selectedCategories);
                    });
            }
    
            if ($request->has('status')) {
                if ($request->status == '1') {
                    $tasks->Active(); 
                } else {
                    $tasks->Completed(); 
                }
            }
    
            $todos = $tasks->orderBy('duedate', 'asc')->paginate(10);
            
       
            $taskCollection = new TaskCollection($todos); 
            // $taskCollection= TaskResource::collection($todos);
    
            $categories = new CategoriesCollection(Category::all());
    
            return success(['todos' => $taskCollection, 'categories' => $categories], 'Tasks retrieved successfully');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
    
    
    public function store(StoreRequest $request)
    {
        try {
            if ($this->allowed()) {
                $task = Task::createTask($request);
                Log::info("task created sucessfully",['id'=>$task->id]);
                 return success(new TaskResource($task), 'Task created successfully',201);
            } else {
                return forbidden(); 
            }
        } catch (\Exception $e) {
            Log::error("an error occured with exception :$e ");
            return error($e->getMessage());
        }
    }
   
    public function update($id, StoreRequest $request)
    {
        try {
            if ($this->allowed()) {
                $task = Task::updateTask($id, $request->all());
                return success(new TaskResource($task), 'Task updated successfully', 200);
            } else {
           
                return forbidden();
            }
        } catch (\Exception $e) {
          
            return error($e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            if ($this->allowed()) {
                Task::deleteTask($id);
    
                return success(null, 'Task deleted successfully', 200);
            } else {
             
                return forbidden();
            }
        } catch (\Exception $e) {
        
            return error($e->getMessage());
        }
    }
    

    public function updateStatus($id)
    {
        try {
            if ($this->allowed()) {
            $todo = Task::findorfail($id);    
            $todo->status = !$todo->status;
            $todo->save();
    
            return success(null, 'Status changed successfully', 200);}
            else {
                return forbidden();
            }
        } catch (\Exception $e) {

            return error($e->getMessage());
        }
    }
    
    
}
