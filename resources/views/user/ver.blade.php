<h1>Detalles del Usuario</h1>

@if(isset($error))
<p style="color: red;">{{ $error }}</p>
@else
<table border="1" cellpadding="5" cellspacing="0" class="table">
    <tr>
        <th>ID</th>
        <td>{{ $usuario['id'] ?? 'Sin ID' }}</td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td>{{ $usuario['name'] ?? 'Sin nombre' }}</td>
    </tr>
    <tr>
        <th>Password</th>
        <td>{{ $usuario['password'] ?? 'Sin password' }}</td>
    </tr>
    <tr>
        <th>Rol</th>
        <td>{{ $usuario['role'] ?? 'Sin rol' }}</td>
    </tr>
    <tr>
        <th>Created_at</th>
        <td>{{ $usuario['created_at'] ?? 'Sin creacion' }}</td>
    </tr>

</table>
@endif

<!-- Enlace para volver a la lista de usuarios con la página actual -->
<a href="{{ route('user.list', ['page' => $page]) }}">
    <button type="button" class="btn btn-primary">Volver al listado</button>
</a>