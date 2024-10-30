@extends('layouts.main')

@section('home-section')
    @include('shared.message')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-5">
            <div class="h2 mx-2">All Todos</div>

            @can('create tasks')
                <a href="{{ route('todos.create') }}" class="btn btn-primary btn-lg">Add Todo</a>
            @endcan
        </div>
        <input class="me-2" type="search" name="search" id="search" placeholder="Search">
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    
    
    </div>
</div>
            
        <table class="table table-stripped" id="todos-table">
            <thead>
                <tr class="table-secondary">
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Due date</th>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'))
                    <th>Action</th>
                    @endif
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
             
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        var table = $('#todos-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ route('todos.index') }}",
                type: 'GET',
                data: function(d) {
                    d.searchTitle = $('#search').val();
                    
              
                    d.categories = [];
                    $('input[name="categories[]"]:checked').each(function() {
                        d.categories.push($(this).val());
                    });

                    d.status = $('input[name="status"]:checked').val();
                },
            },
            columns: [
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'duedate', name: 'duedate' },
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'))
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
                { data: 'status', name: 'status', orderable: false, searchable: false }
            ]
        });

       
        $('#search').on('keyup', function() {
            table.draw();
        });

     
        $('#filterModal').on('hidden.bs.modal', function() {
            table.draw();
        });

        $('.btn-primary').on('click', function() {
            $('#filterModal').modal('hide');
        });
    });

    // function deleteTask(taskId) {
    // $.ajax({
    //     url: "{{ route('todos.delete', '') }}/" + taskId, 
    //     type: 'DELETE',
    //     data: {
    //         _token: '{{ csrf_token() }}' 
    //     },
    //     success: function(response) {
    //         // Reload the DataTable or update UI as needed
    //         $('#todos-table').DataTable().ajax.reload();
    //         alert('Task deleted successfully');
    //     },
    //     error: function(xhr) {
    //         alert('Error deleting task');  
    //     }
    // });
// }

</script>

@endsection
