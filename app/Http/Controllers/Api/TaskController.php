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
/**
 * @OA\Info(
 *     title="Your API Title",
 *     version="1.0.0",
 * )
 */
class TaskController extends Controller
{
    public function allowed(){
        return auth()->user()->hasAnyRole(['editor','admin']);
    }
  
       /**
     * @OA\Get(
     *     path="/api/todos",
     *     summary="Retrieve a list of tasks",
     *     description="Returns a paginated list of tasks with optional search and filtering by categories and status.",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search tasks by title or description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="categories",
     *         in="query",
     *         required=false,
     *         description="Filter tasks by categories",
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter tasks by status: 1 for Active, any other value for Completed",
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
    *         response=200,
    *         description="Tasks retrieved successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="success"),
    *             @OA\Property(property="data", type="object",
    *                 @OA\Property(property="todos", type="object",
    *                     @OA\Property(property="data", type="array",
    *                         @OA\Items(type="object",
    *                             @OA\Property(property="id", type="integer", example=1),
    *                             @OA\Property(property="title", type="string", example="Sample Task"),
    *                             @OA\Property(property="description", type="string", example="This is a sample task."),
    *                             @OA\Property(property="status", type="integer", example=1),
    *                             @OA\Property(property="duedate", type="string", format="date", example="2024-10-30"),
    *                             @OA\Property(property="categories", type="object",
    *                                 @OA\Property(property="data", type="array",
    *                                     @OA\Items(type="object",
    *                                         @OA\Property(property="id", type="integer", example=1),
    *                                         @OA\Property(property="name", type="string", example="Work")
    *                                     )
    *                                 )
    *                             )
    *                         )
    *                     ),
    *                     @OA\Property(property="count", type="integer", example=7)
    *                 ),
    *                 @OA\Property(property="categories", type="object",
    *                     @OA\Property(property="data", type="array",
    *                         @OA\Items(type="object",
    *                             @OA\Property(property="id", type="integer", example=1),
    *                             @OA\Property(property="name", type="string", example="Work")
    *                         )
    *                     )
    *                 )
    *             ),
    *             @OA\Property(property="message", type="string", example="Tasks retrieved successfully")
    *         )
    *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */
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
    
    /**
     * @OA\Post(
     *     path="/api/todos",
     *     summary="Create a new task",
     *     description="Creates a new task with the specified details and assigns categories to it.",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "duedate", "categories"},
     *             @OA\Property(property="title", type="string", example="New Task Title"),
     *             @OA\Property(property="description", type="string", example="Detailed description of the task"),
     *             @OA\Property(property="duedate", type="string", format="date", example="2024-12-31"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1),
     *                 example={1, 2, 3}
     *             )
     *         )
     *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Task created successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="status", type="string", example="success"),
    *             @OA\Property(property="message", type="string", example="Task created successfully"),
    *             @OA\Property(property="data", type="object",
    *                 @OA\Property(property="id", type="integer", example=101),
    *                 @OA\Property(property="title", type="string", example="New Task Title"),
    *                 @OA\Property(property="description", type="string", example="Detailed description of the task"),
    *                 @OA\Property(property="duedate", type="string", format="date", example="2024-12-31"),
    *                 @OA\Property(property="status", type="integer", example=1),
    *                 @OA\Property(
    *                     property="categories",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="id", type="integer", example=1),
    *                         @OA\Property(property="name", type="string", example="Work")
    *                     )
    *                 )
    *             )
    *         )
    *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not allowed to create tasks",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */
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
   
    /**
     * @OA\Put(
     *     path="/api/todos/{id}",
     *     summary="Update an existing task",
     *     description="Updates a task's details and reassigns categories if provided.",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "duedate"},
     *             @OA\Property(property="title", type="string", example="Updated Task Title"),
     *             @OA\Property(property="description", type="string", example="Updated description of the task"),
     *             @OA\Property(property="duedate", type="string", format="date", example="2024-12-31"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=38),
     *                 @OA\Property(property="title", type="string", example="Updated Task Title"),
     *                 @OA\Property(property="description", type="string", example="Updated description of the task"),
     *                 @OA\Property(property="duedate", type="string", format="date", example="2024-12-31"),
     *                 @OA\Property(property="status", type="integer", example=1),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Work")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not allowed to update tasks",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */

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
    
    /**
     * @OA\Delete(
     *     path="/api/delete/{id}",
     *     summary="Delete a task",
     *     description="Deletes the task with the specified ID if the user has permission.",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task deleted successfully"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not allowed to delete tasks",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */

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
    
    /**
     * @OA\Patch(
     *     path="/api/updatestatus/{id}",
     *     summary="Toggle the status of a task",
     *     description="Changes the status of the specified task. Toggles the status between active and inactive.",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to update status",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Status changed successfully"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User not allowed to update task status",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Task not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     ),
     *     security={
     *         {"bearer_token": {}}
     *     },
     * )
     */

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
