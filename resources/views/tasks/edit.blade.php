<h1>Editar Tarea</h1>

<div style="float:left">
    <form method="POST" action="{{ route('tasks.update', ['id' => $tarea['task_id']]) }}">
        @csrf
        <p>
            <label for="nif_cif">NIF/CIF</label>
            <input name="nif_cif" value="{{ $tarea['nif_cif'] }}" />
            {!! $errores->ErrorFormateado('nif_cif') !!}
        </p>
        <p>
            <label for="contact_name">Nombre de Contacto</label>
            <input name="contact_name" value="{{ $tarea['contact_name'] }}" />
            {!! $errores->ErrorFormateado('contact_name') !!}
        </p>
        <p>
            <label for="contact_phone">Teléfono de Contacto</label>
            <input name="contact_phone" value="{{ $tarea['contact_phone'] }}" />
            {!! $errores->ErrorFormateado('contact_phone') !!}
        </p>
        <p>
            <label for="contact_email">Correo de Contacto</label>
            <input name="contact_email" value="{{ $tarea['contact_email'] }}" />
            {!! $errores->ErrorFormateado('contact_email') !!}
        </p>
        <p>
            <label for="address">Dirección</label>
            <input name="address" value="{{ $tarea['address'] }}" />
            {!! $errores->ErrorFormateado('address') !!}
        </p>
        <p>
            <label for="city">Ciudad</label>
            <input name="city" value="{{ $tarea['city'] }}" />
            {!! $errores->ErrorFormateado('city') !!}
        </p>
        <p>
            <label for="postal_code">Código Postal</label>
            <input name="postal_code" value="{{ $tarea['postal_code'] }}" />
            {!! $errores->ErrorFormateado('postal_code') !!}
        </p>
        <p>
            <label for="state">Estado</label>
            <select name="state" id="state">
                <option value="P" {{ $tarea['state'] === 'P' ? 'selected' : '' }}>Esperando a ser aprobada</option>
                <option value="B" {{ $tarea['state'] === 'B' ? 'selected' : '' }}>Pendiente</option>
                <option value="R" {{ $tarea['state'] === 'R' ? 'selected' : '' }}>Realizada</option>
                <option value="C" {{ $tarea['state'] === 'C' ? 'selected' : '' }}>Cancelada</option>
            </select>
            {!! isset($errores) ? $errores->ErrorFormateado('state') : '' !!}
        </p>


        <p>
            <label for="assigned_operator">Operador Asignado</label>
            <input name="assigned_operator" value="{{ $tarea['assigned_operator'] }}" />
            {!! $errores->ErrorFormateado('assigned_operator') !!}
        </p>
        <p>
            <label for="execution_date">Fecha de Ejecución</label>
            <input name="execution_date" type="date" value="{{ $tarea['execution_date'] }}" />
            {!! $errores->ErrorFormateado('execution_date') !!}
        </p>
        <p>
            <label for="previous_notes">Notas Anteriores</label>
            <textarea name="previous_notes">{{ $tarea['previous_notes'] }}</textarea>
            {!! $errores->ErrorFormateado('previous_notes') !!}
        </p>
        <p>
            <label for="post_execution_notes">Notas Posteriores</label>
            <textarea name="post_execution_notes">{{ $tarea['post_execution_notes'] }}</textarea>
            {!! $errores->ErrorFormateado('post_execution_notes') !!}
        </p>
        <p>
            <label for="task_summary_file">Archivo de Resumen</label>
            <input name="task_summary_file" type="file" />
            {!! $errores->ErrorFormateado('task_summary_file') !!}
        </p>
        <p>
            <label for="photos">Fotos</label>
            <input name="photos" type="file" multiple />
            {!! $errores->ErrorFormateado('photos') !!}
        </p>
        <p>
            <button type="submit">Guardar Cambios</button>
        </p>
    </form>
</div>