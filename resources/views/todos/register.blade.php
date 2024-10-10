@extends('layouts.main')
@section('home-section')

<div class="container d-flex justify-content-center align-items-center " style="height:90vh;">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h4 class="text-center mb-4">Register</h4>
                <form action="{{route("todos.register")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input name="name" type="text" class="form-control" id="name" placeholder='example' required>
                        <div class="text-danger">
                        @error('name')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" id="email" placeholder='example@email.com' required>
                        <div class="text-danger">
                        @error('email')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <div class="text-danger">
                        @error('password')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control" id="profile_image" accept="image/*">
                        <div class="text-danger">
                        @error('profile_image')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <p> Already have an account? <a href="{{route('login')}}">login</a></p>
                    <button  class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
@endsection