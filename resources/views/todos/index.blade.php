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
    });
</script>
@endsection
