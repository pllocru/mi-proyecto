<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<h1>Listado de usuarios</h1>

<table border="1" cellpadding="5" cellspacing="0" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Rol</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($usuarios))
        @foreach ($usuarios as $usuario)
        <tr>
            <td>{{ $usuario['id'] ?? 'Sin ID' }}</td>
            <td>{{ $usuario['name'] ?? 'Sin nombre' }}</td>
            <td>{{ $usuario['role'] ?? 'Sin rol' }}</td>
            <td>{{ $usuario['created_at'] ?? 'Sin fecha de creación' }}</td>
            <td>
                <!-- Botón Editar -->
                <a href="{{ route('user.edit', ['id' => $usuario['id']]) }}">
                    <button class="btn btn-warning">Editar</button>
                </a>
                <!-- Botón Borrar -->
                <form action="{{ route('user.borrar', ['id' => $usuario['id'], 'page' => $page]) }}" method="GET">
                    <button type="submit" class="btn btn-danger">Borrar</button>
                </form>

                <!-- Botón Ver -->
                <a href="{{ route('user.ver', ['id' => $usuario['id'], 'page' => $page]) }}">
                    <button class="btn btn-info">Ver</button>
                </a>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6">No hay usuarios disponibles</td>
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