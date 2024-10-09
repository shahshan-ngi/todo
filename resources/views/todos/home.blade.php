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

            </form>
        </div>
        </div>
 
        <a href="{{route("todos.create")}}" class="btn btn-primary btn-lg">Add Todo</a>
    </div>

    <table class="table table-stripped ">
        <tr class="table-secondary">
            <th>Task Name</th>
            <th>Description</th>
            <th>Due date</th>
            <th>Action</th>
            <th>Status</th>
        </tr>
        @if($todos->count() > 0)
            @foreach($todos as $todo)
                <tr valign="middle">
                    <td>{{ $todo->title }}</td>
                    <td>{{ $todo->description }}</td>
                    <td>{{ $todo->duedate }}</td>
                  
                    <td>

                        <a href="{{route("todos.edit",$todo->id)}}" class="btn btn-success btn-sm">Update</a>
                        <form action="{{ route('todos.delete', $todo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    </td>
                    <td>
                    <form action="{{ route('todos.updatestatus', $todo->id) }}" method="POST" style="display:inline;">
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

