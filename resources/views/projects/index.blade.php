<!-- resources/views/projects/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Create New Project</a>
    <div>
        <a href="{{ url('/register') }}" class="btn btn-primary">Регистрация</a>
        <a href="{{ url('/login') }}" class="btn btn-secondary">Авторизация</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->description }}</td>
                <td>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-info">View</a>
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
</html>
