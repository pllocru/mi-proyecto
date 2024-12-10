<?php


function creaSelect($nombreCampo, $valores, $seleccionado = '', $error = '')
{
    $html = "<select name='$nombreCampo'>";

    // Opción por defecto
    $html .= "<option value=''" . ($seleccionado === '' ? 'selected' : '') . ">Seleccionar provincia</option>";

    foreach ($valores as $valor => $texto) {
        $isSelected = ($valor == $seleccionado) ? 'selected' : '';
        $html .= "<option value='$valor' $isSelected>$texto</option>";
    }

    $html .= "</select>";

    if ($error) {
        $html .= "<span style='color:red;'> $error</span>";
    }

    return $html;
}

function validarNIF($nif)
{
    $nif = strtoupper(trim($nif)); // Quita espacios y convierte a mayúsculas

    // Patrones para DNI (8 números y 1 letra) y NIF de extranjeros (X, Y, Z + 7 números + 1 letra)
    if (preg_match('/^[0-9]{8}[A-Z]$/', $nif)) {
        return validarDNILetra($nif);
    } elseif (preg_match('/^[XYZ][0-9]{7}[A-Z]$/', $nif)) {
        return validarNIFExtranjero($nif);
    }

    return false; // Formato inválido
}

function validarDNILetra($dni)
{
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $numero = substr($dni, 0, -1);
    $letra = substr($dni, -1);
    return $letras[$numero % 23] === $letra;
}

function validarNIFExtranjero($nif)
{
    $numeroBase = str_replace(['X', 'Y', 'Z'], ['0', '1', '2'], substr($nif, 0, 1)) . substr($nif, 1, 7);
    return validarDNILetra($numeroBase . substr($nif, -1));
}

function validarEmail($email)
{
    $email = trim($email);


    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función para validar si la contraseña ingresada es correcta.
 *
 * @param string $passwordIngresada La contraseña ingresada por el usuario.
 * @param string $passwordAlmacenada La contraseña cifrada almacenada en la base de datos.
 * @return bool Retorna true si la contraseña es válida, false si no lo es.
 */
function validarPassword($passwordIngresada, $passwordAlmacenada)
{
    // Usar password_verify para comprobar si la contraseña ingresada coincide con la almacenada
    return password_verify($passwordIngresada, $passwordAlmacenada);
}
