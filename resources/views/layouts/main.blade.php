<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('head')
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="bg-secondary"> 
<div class="container py-3 d-flex justify-content-between align-items-center"> 
  
    <a style="text-decoration:none;" href="{{route('todos.index')}}">
        <div class="h1 text-white">Todo List App</div>
    </a>
    @Auth
    <div class="profile-avatar">
    <img src="{{ Storage::url('images/profile/' . Auth::user()->id . '/' . Auth::user()->profile_image) }}"  class="rounded-circle" width="50" height="50">

    </div>
    @endAuth
</div>
      
    </div>
    @yield('home-section')
    
</body>
</html>