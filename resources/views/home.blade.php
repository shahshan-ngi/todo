    @extends('layouts.main')
    @section('home-section')
    <div class="container">
    <div class="d-flex justify-content-between align-items-center my-5"> 

        <div class="h2">All Todos</div>
        <a href="{{route("todo.create")}}" class="btn btn-primary btn-lg">Add Todo</a>
    </div>

    <table class="table table-stripped table-dark">
        <tr>
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
                        <a href="{{route("todo.edit",$todo->id)}}" class="btn btn-success btn-sm">Update</a>
                        <a href="{{route("todo.delete",$todo->id)}}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                    <td><form action="{{ route('todo.updatestatus', $todo->id) }}" method="POST" style="display:inline;">
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
                <td colspan="4" class="text-center">No tasks found.</td>
            </tr>
        @endif
    </table>
    <div class="row">
        {{$todos->links()}}
    </div>
</div>

@endsection

