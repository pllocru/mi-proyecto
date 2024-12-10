<h1>Editar Usuario</h1>

<div style="float:left">
    <form method="POST" action="{{ route('user.update', ['id' => $usuario['id']]) }}">
        @csrf
        <p>
            <label for="name">Nombre:</label>
            <input name="name" value="{{ $usuario['name'] }}" />
            {!! $errores->ErrorFormateado('name') !!}
        </p>
        <p>
            <label for="password">Contraseña (Dejar en blanco para no cambiar):</label>
            <input name="password" type="password" />
            {!! $errores->ErrorFormateado('password') !!}
        </p>
        <p>
            <label for="role">Rol:</label>
            <select name="role">
                <option value="user" {{ $usuario['role'] == 'user' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ $usuario['role'] == 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            {!! $errores->ErrorFormateado('role') !!}
        </p>
        <p>
            <button type="submit">Guardar Cambios</button>
        </p>
    </form>
</div>