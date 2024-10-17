@extends('layouts.main')
@section('home-section')
@include('shared.message')
<div class="container d-flex justify-content-center align-items-center " style="height:75vh;">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h4 class="text-center mb-4">Login</h4>
                <form action="{{route("login")}}" method="POST">
                @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input name="email" type="email" value="{{ old('email')}}" class="form-control" id="email" 
                        placeholder='example@email.com'>
                        <div class="text-danger">
                        @error('email')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control" id="password">
                        <div class="text-danger">
                        @error('password')
                            {{$message}}
                        @enderror
                    </div>
                    </div>
                    <p> do not have an account? <a href="{{route('todos.registerform')}}">register</a></p>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
@endsection