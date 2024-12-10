<h1>Añadir Tarea</h1>

<form method="POST" action="{{ route('tasks.store') }}">
    @csrf
    <div>
        <label for="nif_cif">NIF/CIF:</label>
        <input type="text" name="nif_cif" id="nif_cif" value="{{ old('nif_cif') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('nif_cif') : '' !!}
    </div>

    <div>
        <label for="contact_name">Nombre de Contacto:</label>
        <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('contact_name') : '' !!}
    </div>

    <div>
        <label for="contact_phone">Teléfono:</label>
        <input type="number" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('contact_phone') : '' !!}
    </div>
    <div>
        <label for="description">description:</label>
        <input type="text" name="description" id="description" value="{{ old('description') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('description') : '' !!}
    </div>

    <label for="contact_email">Correo Electrónico:</label>
    <input type="text" name="contact_email" id="contact_email" value="{{ old('contact_email') }}">
    {!! isset($errores) ? $errores->ErrorFormateado('contact_email') : '' !!}
    </div>
    <div>
        <label for="address">Dirección:</label>
        <input type="text" name="address" id="address" value="{{ old('address') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('address') : '' !!}
    </div>
    <div>
        <label for="city">Ciudad:</label>
        <input type="text" name="city" id="city" value="{{ old('city') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('city') : '' !!}
    </div>
    <div>
        <label for="postal_code">Código Postal:</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
        {!! isset($errores) ? $errores->ErrorFormateado('postal_code') : '' !!}
    </div>


    <div>
        <label for="state">Estado:</label>
        <select name="state" id="state">
            <option value="">Seleccionar estado</option>
            <option value="P" {{ old('state') == 'P' ? 'selected' : '' }}>Esperando a ser aprobada</option>
            <option value="B" {{ old('state') == 'B' ? 'selected' : '' }}>Pendiente</option>
            <option value="R" {{ old('state') == 'R' ? 'selected' : '' }}>Realizada</option>
            <option value="C" {{ old('state') == 'R' ? 'selected' : '' }}>Cancelada</option>
        </select>
        {!! isset($errores) ? $errores->ErrorFormateado('state') : '' !!}
    </div>

    <div>
        <label for="previous_notes">Notas Anteriores</label>
        <textarea name="previous_notes">{{ old('previous_notes') }}</textarea>
        {!! isset($errores) ? $errores->ErrorFormateado('state') : '' !!}
    </div>


    <button type="submit">Guardar</button>
</form>