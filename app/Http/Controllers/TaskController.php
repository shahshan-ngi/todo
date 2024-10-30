<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

use App\DataTables\TaskDataTable;
use App\Http\Requests\StoreRequest;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{

    public function allowed(){
        return auth()->user()->hasAnyRole(['editor','admin']);
    }
    // public function index(Request $request)
    // {
    //     try {
   
    //         $tasks = Task::query();
    
    //         // Search filter
    //         if ($request->has('search')) {
    //             $tasks->search($request->search); 
    //         }
    
    //         // Category filter
    //         if ($request->has('categories')) {
    //             $selectedCategories = $request->input('categories');
    //             $tasks->with('categories')
    //                 ->whereHas('categories', function ($query) use ($selectedCategories) {
    //                     $query->whereIn('categories.id', $selectedCategories);
    //                 });
    //         }
    //         if($request->has('status')){
    //             if($request->status=='0'){
    //                 $tasks->Active();
    //             }
    //             else{
    //                 $tasks->Completed();
    //             }
    //         }
    
      
    //         $todos = $tasks->orderBy('duedate', 'asc')->paginate(10);
            

    //         $categories = Category::select('id', 'name')->get();
            
    //         return view('todos.home', compact('todos', 'categories'));
    //     } catch (\Exception $e) {
    //         return redirect(route("todos.index"))->with('error', $e->getMessage());
    //     }
    // }

    
    public function index(Request $request)
    {
        $categories=Category::all();
        if ($request->ajax()) {
            $model = Task::query();
            if ($request->has('searchTitle') && !empty($request->searchTitle)) {
                $model->where('title', 'like', "%" . $request->searchTitle . "%");
            }
            if($request->has('categories') && !empty($request->categories)){
                $selectedCategories = $request->input('categories');
                $model->with('categories')
                    ->whereHas('categories', function ($query) use ($selectedCategories) {
                        $query->whereIn('categories.id', $selectedCategories);
                    });
            }
    
            return DataTables::eloquent($model)
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('description', function ($row) {
                    return $row->description;
                })
                ->addColumn('duedate', function ($row) {
                    return $row->duedate;
                })
                ->addColumn('status', function ($row) {
                    $statusLabel = $row->status == 1 ? 'Pending' : 'Completed';
                    $statusClass = $row->status == 1 ? 'btn-warning' : 'btn-secondary';
                    
                    return '<form action="' . route('todos.updatestatus', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('PATCH') . '
                                <button type="submit" class="btn btn-sm ' . $statusClass . '">' . $statusLabel . '</button>
                            </form>';
                })
                ->addColumn('action', function ($row) {
                    if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor')) {
                        return '<a href="' . route("todos.edit", $row->id) . '" class="btn btn-success btn-sm">Update</a>
                                <form action="' . route('todos.delete', $row->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>';
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    
        return view('todos.index',compact('categories'));
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
                return redirect(route('todos.index'))->with('error', '403 Forbidden, you are not authorized to access this url');
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

    public function update($id, StoreRequest $request)
    {
        try {
            if($this->allowed()){
                Task::updateTask($id, $request->all());
                return redirect(route("todos.index"))->with('success', 'Updated successfully');
            }else{
                return redirect(route("todos.index"))->with('error', '403 Forbidden, you are not authorized to access this url');
            }
     
        } catch (\Exception $e) {
            return redirect(route("todos.update"))->with('error', 'Something went wrong')->withInput();
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