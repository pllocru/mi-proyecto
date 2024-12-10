<?php

namespace App\Models;



class Paginador
{
    private $data; // Array de datos
    private $perPage; // Elementos por página
    private $currentPage; // Página actual

    public function __construct(array $data, $perPage = 10, $currentPage = 1)
    {
        $this->data = $data;
        $this->perPage = $perPage;
        $this->currentPage = max(1, $currentPage); // Asegurarse de que sea al menos 1
    }

    public function setPaginador(array $data, $perPage = 10, $currentPage = 1)
    {
        $this->data = $data;
        $this->perPage = $perPage;
        $this->currentPage = max(1, $currentPage); // Asegurarse de que sea al menos 1
    }

    public function getResults()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->data, $offset, $this->perPage);
    }

    /**
     * Calcula el número total de páginas.
     */
    public function getTotalPages()
    {
        return ceil(count($this->data) / $this->perPage);
    }

    /**
     * Genera los enlaces de paginación.
     */
    public function getPaginationLinks($baseUrl)
    {

        $totalPages = $this->getTotalPages();
        $links = '';

        // Enlace para ir a la primera página
        if ($this->currentPage > 1) {
            $links .= "<a href='{$baseUrl}?page=1'>← </a> ";
        }

        // Solo se muestran la página actual y la siguiente
        if ($this->currentPage > 1) {
            $links .= "<a href='{$baseUrl}?page=" . ($this->currentPage - 1) . "'>" . $this->currentPage - 1 . "</a> ";
        }

        $links .= "<span>{$this->currentPage}</span> ";

        // Mostrar la siguiente página si no estamos en la última
        if ($this->currentPage < $totalPages) {
            $links .= "<a href='{$baseUrl}?page=" . ($this->currentPage + 1) . "'>" . $this->currentPage + 1 . "</a> ";
        }

        // Flecha para ir al final
        if ($this->currentPage < $totalPages) {
            $links .= "<a href='{$baseUrl}?page={$totalPages}'>→ </a>";
        }

        return $links;
    }
}
