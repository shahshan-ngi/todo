<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;

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
                if ($request->status == '0') {
                    $tasks->Active(); 
                } else {
                    $tasks->Completed(); 
                }
            }
        
            $todos = $tasks->orderBy('duedate', 'asc')->paginate(10);
    
            $categories = Category::select('id', 'name')->get();
    
         
            return response()->json([
                'status' => 'success',
                'data' => [
                    'todos' => $todos,
                    'categories' => $categories
                ]
            ], 200); 
    
        } catch (\Exception $e) {
         
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function store(StoreRequest $request)
    {
        try {
            if ($this->allowed()) {

                $task=Task::createTask($request);
            
                return response()->json([
                    'status' => 'success',
                    'data'=>$task,
                    'message' => 'Task created successfully'
                ], 201); 
            } else {
               
                return response()->json([
                    'status' => 'error',
                    'message' => '403 Forbidden, you are not authorized to access this resource'
                ], 403); 
            }
    
        } catch (\Exception $e) {
         
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500); 
        }
    }
    
   
    public function update($id, StoreRequest $request)
    {
        try {
   
            if ($this->allowed()) {
     
                $task=Task::updateTask($id, $request->all());
    
       
                return response()->json([
                    'status' => 'success',
                    'data'=>$task,
                    'message' => 'Task updated successfully'
                ], 200); 
            } else {
          
                return response()->json([
                    'status' => 'error',
                    'message' => '403 Forbidden, you are not authorized to access this resource'
                ], 403); 
            }
    
        } catch (\Exception $e) {
      
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong during the update process'
            ], 500); 
        }
    }
    
    public function delete($id)
    {
        try {
 
            if ($this->allowed()) {

                Task::deleteTask($id);
    

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task deleted successfully'
                ], 200);
            } else {
      
                return response()->json([
                    'status' => 'error',
                    'message' => '403 Forbidden, you are not authorized to access this resource'
                ], 403);
            }
    
        } catch (\Exception $e) {    return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong during the delete process'
            ], 500);
        }
    }
    

    public function updateStatus($id)
    {
        try {

            $todo = Task::findorfail($id);    
            $todo->status = !$todo->status;
            $todo->save();
    

            return response()->json([
                'status' => 'success',
                'message' => 'Status changed successfully',
      
            ], 200); 
    
        } catch (\Exception $e) {
        
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], 500); 
        }
    }
    
}
