<h1>Añadir Usuario</h1>

<form method="POST" action="{{ route('user.store') }}">
    @csrf <!-- Token de seguridad de Laravel -->

    <div>
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}">
        {!! isset($errores) ? $errores->errorFormateado('name') : '' !!}
    </div>

    <div>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">
        {!! isset($errores) ? $errores->errorFormateado('password') : '' !!}
    </div>

    <div>
        <label for="role">Rol:</label>
        <select name="role" id="role">
            <option value="">Selecciona</option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        {!! isset($errores) ? $errores->errorFormateado('role') : '' !!}
    </div>

    <button type="submit">Guardar</button>
</form>