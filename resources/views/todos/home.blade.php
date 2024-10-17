    @extends('layouts.main')
    @section('home-section')
    @include('shared.message')
    <div class="container">
    <div class="d-flex justify-content-between align-items-center my-5"> 
        <div class="d-flex  align-items-center ">
        <div class="h2 mx-2">All Todos</div>
        <div>
            <form action="{{route("todos.index")}}" method='GET'>
                <input class="me-2" type="search" name="search" placeholder="Search">
                <button type="button" class="btn btn-light ms-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-filter"></i>
                </button>
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('todos.index') }}" method='GET'>
                <div class="d-flex justify-content-center align-items-start my-5">
                <div>
                <div><h4>Categories</h4></div>
                @foreach($categories as $category)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="category-{{ $category->id }}" name="categories[]"  {{ in_array($category->id, request()->get('categories', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="category-{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                </div>
                @endforeach
                </div>
                <div class="d-block mx-5">
                    <div><h4>Status</h4></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="status" id="completed"    {{ request()->get('status') === '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="completed">
                            Completed
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="status" id="pending"    {{ request()->get('status') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pending">
                            Pending
                        </label>
                    </div>
                </div>
                </div>

            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </div>
</div>
                
            </form>
        </div>
        </div>
        @can(['create tasks'])
        <a href="{{route("todos.create")}}" class="btn btn-primary btn-lg">Add Todo</a>
        @endcan
    </div>
    
    <table class="table table-stripped ">
        <tr class="table-secondary">
            <th>Task Name</th>
            <th>Description</th>
            <th>Due date</th>
            @canany(['edit tasks','delete tasks'])
            <th>Action</th>
            @endcan
          
            <th>Status</th>
        </tr>
        @if($todos->count() > 0)
            @foreach($todos as $todo)
                <tr valign="middle">
                    <td>{{ $todo->title }}</td>
                    <td>{{ $todo->description }}</td>
                    <td>{{ $todo->duedate }}</td>
                    @canany(['edit tasks','delete tasks'])
                    <td>    
                        <a href="{{route("todos.edit",$todo->id)}}" class="btn btn-success btn-sm">Update</a>
                        <form action="{{ route('todos.delete', $todo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    </td>
                    @endcan
                    <td>
                        <form action="{{route('todos.updatestatus', $todo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $todo->status == 1 ? 'btn-warning' : 'btn-secondary' }}">
                            {{ $todo->status == 1 ? 'Pending' : 'Completed' }}
                        </button>
                    </form>
             
                </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center">No tasks found</td>
            </tr>
        @endif
    </table>
    <div class="row">
        {{$todos->links()}}
    </div>
</div>

@endsection

