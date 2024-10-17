@extends('layouts.main')

@section('home-section')

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-5"> <!-- Margin 5-->
        <div class="h2">Update Todo</div>
        <a href="{{ route('todos.index') }}" class="btn btn-primary btn-lg">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('todos.update', $todo->id) }}" method="POST">
                @method('PATCH')
                @csrf
                
                <!-- Task Name -->
                <label for="" class="form-label mt-4">Task Name</label>
                <input type="text" name="title" class="form-control" id=""
                       value="{{ old('title', $todo->title) }}">
                <div class="text-danger">
                    @error('title')
                        {{ $message }}
                    @enderror
                </div>
                
                <!-- Description -->
                <label for="" class="form-label mt-4">Description</label>
                <input type="text" name="description" class="form-control" id=""
                       value="{{ old('description', $todo->description) }}">
                <div class="text-danger">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>
                
                <!-- Due Date -->
                <label for="" class="form-label mt-4">Due Date</label>
                <input type="date" name="duedate" class="form-control" id=""
                        value="{{ old('duedate', date('Y-m-d', strtotime($todo->duedate))) }}">
                <div class="text-danger">
                    @error('duedate')
                        {{ $message }}
                    @enderror
                </div>

                <input type="number" name="id" value="{{ $todo->id }}" hidden>

                <button class="btn btn-primary btn-lg mt-4">Update Todo</button>
            </form>
        </div>
    </div>
</div>

@endsection
