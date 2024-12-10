<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Enlazar al archivo de estilo externo -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        <form method="POST">
            @csrf <!-- Esta directiva incluye automáticamente el token CSRF -->
            <div>
                <label for="username">Nombre de Usuario:</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="text" name="password" id="password">
            </div>
            <div>
                <label for="remember_me">
                    <input type="checkbox" name="remember_me" id="remember_me"> Mantener sesión iniciada
                </label>
            </div>
            <div>
                <button type="submit">Iniciar Sesión</button>
            </div>


        </form>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
    </div>

</body>

</html>