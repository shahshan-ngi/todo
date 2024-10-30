<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('head')
    <title>Document</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons (choose one version) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
    
   
</head>
<body>
    <div class="bg-secondary"> 
        <div class="container py-3 d-flex justify-content-between align-items-center"> 
            <a style="text-decoration:none;" href="{{route('todos.index')}}">
                <div class="h1 text-white">Todo List App</div>
            </a>
            @auth
            <div class="profile-avatar">
                <img src="{{ Storage::url('images/profile/' . Auth::user()->id . '/' . Auth::user()->profile_image) }}"  class="rounded-circle" width="50" height="50">
            </div>
            @endauth
        </div>
    </div>

    @yield('home-section')


</body>
</html>
