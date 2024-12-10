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
    <tr>
        <th>Notas Posteriores</th>
        <td>{{ $tarea['post_execution_notes'] ?? 'Sin notas' }}</td>
    </tr>
    <tr>
        <th>Fotos</th>
        <td>
            @php
            $photos = !empty($tarea['photos']) ? json_decode($tarea['photos'], true) : [];
            @endphp
            @if(!empty($photos))
            @foreach($photos as $photo)
            <img src="{{ asset('uploads/photos/' . $photo) }}" alt="Foto" width="100">
            @endforeach
            @else
            Sin fotos
            @endif
        </td>

    </tr>
    <tr>
        <th>Archivo de Resumen</th>
        <td>
            @if(!empty($tarea['task_summary_file']))
            <a href="{{ asset('uploads/task_summaries/' . $tarea['task_summary_file']) }}" target="_blank">Ver Resumen</a>
            @else
            Sin archivo de resumen
            @endif
        </td>
    </tr>
</table>
@endif

<!-- Botón para marcar la tarea como completada -->
<a href="{{ route('tasks.markAsCompleted', ['id' => $tarea['task_id']]) }}">
    <button type="button" class="btn btn-success">Completar</button>
</a>

<!-- Botón para volver a la lista de tareas -->
<form method="GET" action="{{ route('tasks.list') }}">
    <button type="submit">Volver</button>
</form>