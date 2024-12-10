<?php

namespace App\Models;

/**
 * Clase que gestiona los errores producidos, permitiendo registrarlos y formatearlos para su salida.
 */
class GestorErrores
{
    /**
     * Lista de errores registrados. Se almacena una descripción por campo.
     * @var array
     */
    private $errores = [];

    /**
     * Prefijo de formato para los mensajes de error.
     * @var string
     */
    private $formatPrefix;

    /**
     * Sufijo de formato para los mensajes de error.
     * @var string
     */
    private $formatSuffix;

    /**
     * Constructor que inicializa el gestor de errores con etiquetas de formato opcionales.
     * 
     * @param string $formatPrefix Prefijo de formato para los errores.
     * @param string $formatSuffix Sufijo de formato para los errores.
     */
    public function __construct(string $formatPrefix = '', string $formatSuffix = '')
    {
        $this->formatPrefix = $formatPrefix;
        $this->formatSuffix = $formatSuffix;
    }

    /**
     * Registra un error para un campo específico.
     * 
     * @param string $campo Nombre del campo con error.
     * @param string $descripcion Descripción del error.
     * @return void
     */
    public function anotaError(string $campo, string $descripcion): void
    {
        $this->errores[$campo] = $descripcion;
    }

    /**
     * Verifica si hay errores registrados.
     * 
     * @return bool `true` si hay errores, `false` en caso contrario.
     */
    public function hayErrores(): bool
    {
        return !empty($this->errores);
    }

    /**
     * Verifica si hay un error registrado para un campo específico.
     * 
     * @param string $campo Nombre del campo.
     * @return bool `true` si hay un error, `false` en caso contrario.
     */
    public function hayError(string $campo): bool
    {
        return isset($this->errores[$campo]);
    }

    /**
     * Devuelve la descripción del error para un campo específico.
     * 
     * @param string $campo Nombre del campo.
     * @return string Descripción del error o una cadena vacía si no hay error.
     */
    public function error(string $campo): string
    {
        return $this->errores[$campo] ?? '';
    }

    /**
     * Obtiene todos los errores registrados.
     * 
     * @return array Lista de errores.
     */
    public function getErrores(): array
    {
        return $this->errores;
    }

    /**
     * Devuelve el error formateado para un campo específico.
     * 
     * @param string $campo Nombre del campo.
     * @return string Error formateado o una cadena vacía si no hay error.
     */
    public function errorFormateado(string $campo): string
    {
        if ($this->hayError($campo)) {
            return $this->formatPrefix . $this->error($campo) . $this->formatSuffix;
        }
        return '';
    }

    /**
     * Devuelve todos los errores formateados como un bloque de HTML.
     * 
     * @return string Errores formateados en una lista HTML.
     */
    public function renderErrores(): string
    {
        if ($this->hayErrores()) {
            $output = '';
            foreach ($this->errores as $campo => $mensaje) {
                $output .= $this->formatPrefix . $mensaje . $this->formatSuffix . "\n";
            }
            return $output;
        }
        return '';
    }

    /**
     * Limpia todos los errores registrados.
     * 
     * @return void
     */
    public function limpiarErrores(): void
    {
        $this->errores = [];
    }
}
