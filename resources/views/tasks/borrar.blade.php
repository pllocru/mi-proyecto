<h1>Detalles de la Tarea</h1>

@if(isset($error))
<p style="color: red;">{{ $error }}</p>
@else
<table border="1" cellpadding="5" cellspacing="0" class="table">
    <tr>
        <th>ID</th>
        <td>{{ $tarea['task_id'] ?? 'Sin ID' }}</td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td>{{ $tarea['description'] ?? 'Sin descripción' }}</td>
    </tr>
    <tr>
        <th>Persona de Contacto</th>
        <td>{{ $tarea['contact_name'] ?? 'Sin contacto' }}</td>
    </tr>
    <tr>
        <th>Teléfono de Contacto</th>
        <td>{{ $tarea['contact_phone'] ?? 'Sin teléfono' }}</td>
    </tr>
    <tr>
        <th>Correo Electrónico</th>
        <td>{{ $tarea['contact_email'] ?? 'Sin correo' }}</td>
    </tr>
    <tr>
        <th>Provincia</th>
        <td>{{ $tarea['province_code'] ?? 'Sin provincia' }}</td>
    </tr>
    <tr>
        <th>Estado</th>
        <td>{{ $tarea['state'] ?? 'Sin estado' }}</td>
    </tr>
    <tr>
        <th>Fecha de Ejecución</th>
        <td>{{ $tarea['execution_date'] ?? 'Sin fecha' }}</td>
    </tr>
</table>
@endif

<h2>¿Estás seguro de que deseas borrar esta tarea?</h2>

<form action="{{ route('tasks.borrar', ['id' => $tarea['task_id']]) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Sí, borrar</button>
</form>

<a href="{{ route('tasks.list') }}">
    <button type="button" class="btn btn-primary">Volver al listado</button>
</a>