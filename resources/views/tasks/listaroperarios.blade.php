<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<h1>Listado de tareas</h1>

<table border="1" cellpadding="5" cellspacing="0" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Contacto</th>
            <th>Día de completado</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($tareas))
        @foreach ($tareas as $tarea)
        <tr>
            <td>{{ $tarea['task_id'] ?? 'Sin ID' }}</td>
            <td>{{ $tarea['description'] ?? 'Sin descripción' }}</td>
            <td>{{ $tarea['contact_phone'] ?? 'Sin teléfono' }}</td>
            <td>{{ $tarea['execution_date'] ?? 'Sin día' }}</td>
            <td>{{ $tarea['state'] ?? 'Sin estado' }}</td>
            <td>
                @if ($tarea['state'] === 'Pendiente')
                <form method="POST" action="{{ route('tasks.complete', ['id' => $tarea['task_id']]) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Completar</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6">No hay tareas disponibles</td>
        </tr>
        @endif
    </tbody>
</table>

<!-- Paginación -->
<div class="pagination">
    @if (!empty($paginationLinks))
    <div class="pagination-links">
        {!! $paginationLinks !!} <!-- Utiliza {!! para renderizar el HTML directamente -->
    </div>
    @endif
</div>