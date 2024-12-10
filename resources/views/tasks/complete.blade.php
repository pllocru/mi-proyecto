<h1>Completar</h1>

<div style="float:left">
    <form method="POST" action="{{ route('tasks.complete', ['id' => $tarea['task_id']]) }}" enctype="multipart/form-data">
        @csrf

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
            <input name="photos[]" type="file" multiple />
            {!! $errores->ErrorFormateado('photos') !!}
        </p>
        <p>
            <button type="submit">Guardar Cambios</button>
        </p>
    </form>
</div>