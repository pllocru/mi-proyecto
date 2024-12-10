<!-- resources/views/error.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>

<body>
    <h1>Error</h1>
    <p>{{ $mensaje }}</p>
    <a href="{{ route('tasks.list') }}">Volver al listado de tareas</a>
</body>

</html>