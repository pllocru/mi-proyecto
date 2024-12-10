<?php

namespace App\Http\Controllers;

use App\Models\TareasModel;
use App\Models\GestorErrores;
use App\Models\Paginador;

include_once realpath(__DIR__ . '/../../../helpers/Funciones.php');

/**
 * Controlador para la gestión de tareas
 */
class TareasCtrl
{
    protected $model = null;
    protected $errores = null;

    public function __construct()
    {
        $this->model = new TareasModel();

        // Inicializamos el gestor de errores que utilizaremos en la vista
        $this->errores = new GestorErrores(
            '<span style="color:red; background:#EEE; padding:.2em 1em; margin:1em">',
            '</span>'
        );
    }
    /**
     * Redirige a la página de inicio
     */
    public function Index()
    {
        return $this->Listar();
    }

    /**
     * Muestra los detalles de una tarea
     */
    public function Ver($id)
    {
        // Obtiene la tarea por ID
        $tarea = $this->model->getTareaById($id);

        // Si la tarea no existe
        if (!$tarea) {
            return $this->renderView('Error', view('error', ['mensaje' => 'Tarea no encontrada']));
        }

        // Obtener el número de página de la URL (si existe)
        $page = $_GET['page'] ?? 1; // Si no existe, asumir la página 1

        // Pasar la tarea y el número de página actual a la vista
        return $this->renderView('Detalles de la Tarea', view('tasks.ver', [
            'tarea' => $tarea,
            'page' => $page // Pasar el número de página
        ]));
    }

    public function confirmarBorrar($id)
    {
        // Obtener la tarea por ID
        $tarea = $this->model->getTareaById($id);

        if (!$tarea) {
            return $this->renderView('Error', view('error', ['mensaje' => 'Tarea no encontrada']));
        }

        // Mostrar la vista de confirmación con la tarea
        return $this->renderView('Confirmar Borrar', view('tasks.borrar', [
            'tarea' => $tarea,
        ]));
    }

    // Método para mostrar la confirmación de borrado
    public function borrar($id)
    {
        $tarea = $this->model->getTareaById($id);

        if ($tarea) {
            // Eliminar la tarea
            $this->model->deleteTarea($id);

            // Obtener el número de página de la URL, si está disponible
            $page = $_GET['page'] ?? 1;

            // Realizar la redirección a la URL '/listar' con la página actual
            header('Location: ' . route('tasks.list') . '?page=' . $page);
            exit(); // Asegurarse de que el script termine después de la redirección
        } else {
            // Si no existe la tarea, mostrar error
            return $this->renderView('Error', view('error', ['mensaje' => 'Tarea no encontrada']));
        }
    }

    /**
     * Muestra la página de inicio
     */
    public function Inicio()
    {
        return $this->renderView('Página de inicio', view('inicio'));
    }
    /**
     * Muestra la lista de tareas
     */
    public function Listar()
    {
        // Obtiene el número de página actual desde la URL, por defecto es la página 1
        $page = $_GET['page'] ?? 1;

        // Obtiene todas las tareas
        $tareas = $this->model->getAllTareas();

        // Verifica si cada tarea tiene un 'state' y lo traduce
        foreach ($tareas as &$tarea) {
            if (isset($tarea['state'])) {
                $tarea['state'] = $this->getEstadoTarea($tarea['state']);
            } else {
                $tarea['state'] = 'Desconocido';  // Valor predeterminado si no tiene estado
            }
        }

        // Instancia el paginador (10 tareas por página)
        $paginador = new Paginador($tareas, 10, $page);

        // Obtiene los resultados de la página actual
        $tareasPaginadas = $paginador->getResults();

        // Genera los enlaces de paginación
        $paginationLinks = $paginador->getPaginationLinks(route('tasks.list')); // Asegúrate de pasar la URL base

        // Renderiza la vista con las tareas paginadas
        return $this->renderView('Listado de tareas', view('tasks.listar', [
            'tareas' => $tareasPaginadas,
            'paginationLinks' => $paginationLinks,
            'page' => $page, // Pasamos el número de página a la vista
        ]));
    }

    public function ListarOperario()
    {
        // Obtiene el número de página actual desde la URL, por defecto es la página 1
        $page = $_GET['page'] ?? 1;

        // Obtiene todas las tareas
        $tareas = $this->model->getAllTareas();

        // Verifica si cada tarea tiene un 'state' y lo traduce
        foreach ($tareas as &$tarea) {
            if (isset($tarea['state'])) {
                $tarea['state'] = $this->getEstadoTarea($tarea['state']);
            } else {
                $tarea['state'] = 'Desconocido';  // Valor predeterminado si no tiene estado
            }
        }

        // Instancia el paginador (10 tareas por página)
        $paginador = new Paginador($tareas, 10, $page);

        // Obtiene los resultados de la página actual
        $tareasPaginadas = $paginador->getResults();

        // Genera los enlaces de paginación
        $paginationLinks = $paginador->getPaginationLinks(route('operario.list')); // Asegúrate de pasar la URL base

        // Renderiza la vista con las tareas paginadas y el rol de usuario
        return $this->renderView('Listado de tareas', view('tasks.listaroperarios', [
            'tareas' => $tareasPaginadas,
            'paginationLinks' => $paginationLinks,
            'page' => $page, // Pasamos el número de página a la vista
        ]), 'user'); // Cambiar el rol a 'user' aquí
    }


    public function add()
    {
        $provincias = $this->model->getAllProvinces();

        // Verificar que $provincias sea un array asociativo
        $provinciasFormatted = [];
        foreach ($provincias as $provincia) {
            $provinciasFormatted[$provincia['cod']] = $provincia['nombre'];
        }

        return $this->renderView('Añadir', view('tasks.add', [
            'provincias' => $provinciasFormatted,
            'errores' => $this->errores,
        ]));
    }



    /**
     * Guarda una nueva tarea en la base de datos.
     */
    public function store()
    {
        // Obtener todas las provincias desde el modelo
        $provincias = $this->model->getAllProvinces();

        // Obtener el número de página de la URL, por defecto es la página 1
        $page = $_GET['page'] ?? 1;

        // Verifica si se envió el formulario
        if ($_POST) {
            // Filtrar y validar los campos del formulario
            $this->FiltraCamposPost();

            // Si no hay errores, proceder a guardar la tarea
            if (!$this->errores->hayErrores()) {
                // Obtener los datos del formulario
                $tarea = [
                    'nif_cif' => htmlspecialchars(trim(VPost('nif_cif'))),
                    'contact_name' => htmlspecialchars(trim(VPost('contact_name'))),
                    'contact_phone' => htmlspecialchars(trim(VPost('contact_phone'))),
                    'contact_email' => htmlspecialchars(trim(VPost('contact_email'))),
                    'address' => htmlspecialchars(trim(VPost('address'))),
                    'city' => htmlspecialchars(trim(VPost('city'))),
                    'postal_code' => htmlspecialchars(trim(VPost('postal_code'))),
                    'province_code' => htmlspecialchars(VPost('province_code')),
                    'state' => htmlspecialchars(VPost('state')),
                    'assigned_operator' => htmlspecialchars(VPost('assigned_operator')),
                    'execution_date' => htmlspecialchars(VPost('execution_date')),
                    'previous_notes' => htmlspecialchars(trim(VPost('previous_notes'))),
                ];

                // Llamar al modelo para guardar la tarea
                $lastInsertId = $this->model->addTarea($tarea);

                if ($lastInsertId) {
                    // Redirigir al listado con la página actual
                    header('Location: ' . route('tasks.list') . '?page=' . $page);
                    exit();
                } else {
                    // Mostrar un mensaje de error si no se pudo guardar
                    return $this->renderView('Error', view('tasks.add', [
                        'errores' => ['error' => 'No se pudo crear la tarea.'],
                        'provincias' => $provincias,
                        'page' => $page,
                    ]));
                }
            }

            // Si hay errores, regresar al formulario con los errores y datos ingresados
            return $this->renderView('Errores', view('tasks.add', [
                'tarea' => $_POST,
                'errores' => $this->errores,
                'provincias' => $provincias,
                'page' => $page,
            ]));
        }

        // Si el formulario no se ha enviado, cargar la vista del formulario vacío
        return $this->renderView('Añadir Tarea', view('tasks.add', [
            'provincias' => $provincias,
            'page' => $page,
        ]));
    }




    public function updateTarea($id)
    {
        // Recoger los datos del formulario
        $data = $_POST;

        // Eliminar el token CSRF
        unset($data['_token']);

        // Verifica que "state" esté presente en $data
        if (!isset($data['state'])) {
        }

        // Actualizar la tarea con los nuevos datos
        $updated = $this->model->updateTarea($id, $data);

        if ($updated) {
            // Redirigir con un mensaje de éxito
            header('Location: ' . route('tasks.list'));
            exit();
        } else {
            // Redirigir con un mensaje de error
            header('Location: ' . route('tasks.edit'));
            exit();
        }
    }



    /**
     * Edita una tarea existente
     */
    public function Edit($id)
    {
        if ($_POST) {
            // Validar y procesar los datos del formulario
            $this->FiltraCamposPost();

            // Recoger los datos del formulario
            $tarea = [
                'nif_cif' => VPost('nif_cif'),
                'contact_name' => VPost('contact_name'),
                'contact_phone' => VPost('contact_phone'),
                'contact_email' => VPost('contact_email'),
                'address' => VPost('address'),
                'city' => VPost('city'),
                'postal_code' => VPost('postal_code'),
                'province_code' => VPost('province_code'),
                'state' => VPost('state'),
                'assigned_operator' => VPost('assigned_operator'),
                'execution_date' => VPost('execution_date'),
                'previous_notes' => VPost('previous_notes'),
                'post_execution_notes' => VPost('post_execution_notes'),
                'task_summary_file' => VPost('task_summary_file'),
                'photos' => VPost('photos'),
            ];

            // Verificar si hay errores
            if (!$this->errores->HayErrores()) {
                // Actualizar la tarea
                $this->model->updateTarea($id, $tarea);

                // Redirigir al listado con un mensaje de éxito
                return $this->renderView('Listado de tareas', view('listar', [
                    'tareas' => $this->model->getAllTareas(), // Obtener todas las tareas actualizadas
                    'success' => 'Tarea actualizada correctamente.',
                ]));
            }
        } else {
            // Obtener la tarea existente
            $tarea = $this->model->getTareaById($id);

            // Si no existe la tarea, mostrar error
            if (!$tarea) {
                return $this->renderView('Error', view('error', ['mensaje' => 'No se encontró la tarea.']));
            }
        }

        return $this->renderView('Editar Tarea', view('tasks.edit', [
            'tarea' => $tarea,
            'errores' => $this->errores,
        ]));
    }


    /**
     * Filtra y valida los campos enviados por POST
     */
    protected function FiltraCamposPost()
    {
        // Obtener y limpiar los valores del formulario
        $nif_cif = trim(VPost('nif_cif'));
        $contact_name = trim(VPost('contact_name'));
        $contact_phone = trim(VPost('contact_phone'));
        $contact_email = trim(VPost('contact_email'));
        $address = trim(VPost('address'));
        $city = trim(VPost('city'));
        $postal_code = trim(VPost('postal_code'));
        $province_code = VPost('province_code');
        $state = VPost('state');
        $execution_date = VPost('execution_date');
        $previous_notes = VPost('previous_notes');

        // Validar los datos de POST
        if ($nif_cif === '') {
            $this->errores->AnotaError('nif_cif', 'El campo NIF/CIF es obligatorio.');
        }

        if ($contact_name === '') {
            $this->errores->AnotaError('contact_name', 'El nombre de contacto es obligatorio.');
        }

        if ($contact_phone === '') {
            $this->errores->AnotaError('contact_phone', 'El teléfono de contacto es obligatorio.');
        } elseif (!preg_match('/^[0-9]{9}$/', $contact_phone)) {
            $this->errores->AnotaError('contact_phone', 'El teléfono debe contener 9 dígitos.');
        }

        if ($contact_email === '') {
            $this->errores->AnotaError('contact_email', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            $this->errores->AnotaError('contact_email', 'El correo electrónico no es válido.');
        }

        if ($address === '') {
            $this->errores->AnotaError('address', 'La dirección es obligatoria.');
        }

        if ($city === '') {
            $this->errores->AnotaError('city', 'La ciudad es obligatoria.');
        }

        if ($postal_code === '') {
            $this->errores->AnotaError('postal_code', 'El código postal es obligatorio.');
        } elseif (!preg_match('/^\d{5}$/', $postal_code)) {
            $this->errores->AnotaError('postal_code', 'El código postal debe tener 5 dígitos.');
        }

        if (!$previous_notes) {
            $this->errores->AnotaError('previous_notes', 'La provincia es obligatoria.');
        }

        if (!$state) {
            $this->errores->AnotaError('state', 'El estado es obligatorio.');
        }

        if ($execution_date === '') {
            $this->errores->AnotaError('execution_date', 'La fecha de ejecución es obligatoria.');
        } elseif (!strtotime($execution_date)) {
            $this->errores->AnotaError('execution_date', 'La fecha de ejecución no es válida.');
        }
    }

    public function complete($id)
    {
        $tarea = $this->model->getTareaById($id);

        if ($tarea && $tarea['state'] === 'Pendiente') {
            $this->model->updateTarea($id, ['state' => 'Completada']);
        }

        // Redirigir manualmente al listado de tareas para el usuario
        header('Location: ' . route('tasks.list'));
        exit(); // Asegúrate de detener la ejecución después de redirigir
    }

    public function completeTask($id)
    {
        // Obtener los datos de la tarea existente
        $tarea = $this->model->getTareaById($id);

        if (!$tarea) {
            // Si la tarea no existe, muestra un error con la plantilla de usuario
            return $this->renderView('Error', view('error', ['mensaje' => 'Tarea no encontrada']), 'user');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar y procesar los datos del formulario
            $postExecutionNotes = trim(VPost('post_execution_notes'));
            $taskSummaryFile = $_FILES['task_summary_file'] ?? null;
            $photos = $_FILES['photos'] ?? null;

            // Validar campos
            if ($postExecutionNotes === '') {
                $this->errores->AnotaError('post_execution_notes', 'Las notas posteriores son obligatorias.');
            }

            // Obtener el nombre del archivo de resumen (si existe)
            $taskSummaryFileName = $taskSummaryFile['name'] ?? null;

            // Obtener los nombres de las fotos (si existen)
            $photosNames = [];
            if (isset($photos) && is_array($photos['name'])) {
                foreach ($photos['name'] as $name) {
                    $photosNames[] = $name;
                }
            }

            // Verificar errores
            if (!$this->errores->HayErrores()) {
                // Actualizar los datos de la tarea
                $updateData = [
                    'state' => 'Esperando ser aprobada',
                    'post_execution_notes' => $postExecutionNotes,
                    'task_summary_file' => $taskSummaryFileName, // Guardar solo el nombre del archivo
                    'photos' => json_encode($photosNames), // Guardar los nombres de las fotos como JSON
                ];

                // Actualizar la tarea
                $this->model->updateTarea($id, $updateData);

                // Redirigir al listado del operario
                header('Location: ' . route('operario.list'));
                exit();
            }

            // Si hay errores, devolver la vista con errores y la plantilla de usuario
            return $this->renderView('Completar Tarea', view('tasks.complete', [
                'tarea' => $tarea,
                'errores' => $this->errores,
            ]), 'user');
        }

        // Renderizar la vista del formulario para completar la tarea con la plantilla de usuario
        return $this->renderView('Completar Tarea', view('tasks.complete', [
            'tarea' => $tarea,
            'errores' => $this->errores,
        ]), 'user');
    }

    public function VerTarea($id)
    {
        $tarea = $this->model->getTareaById($id);

        if (!$tarea) {
            return $this->renderView('Error', view('error', ['mensaje' => 'Tarea no encontrada']));
        }

        $page = $_GET['page'] ?? 1;

        return $this->renderView('Detalles de la Tarea', view('tasks.completar', [
            'tarea' => $tarea,
            'page' => $page,
        ]));
    }

    public function markAsCompleted($id)
    {
        $tarea = $this->model->getTareaById($id);

        if ($tarea && $tarea['state'] !== 'Completada') {
            $this->model->updateTarea($id, ['state' => 'Realizada']);
        }

        // Redirigir manualmente al listado de tareas
        header('Location: ' . route('tasks.list'));
        exit();
    }


    /**
     * Devuelve una descripción del estado de la tarea
     */
    public function getEstadoTarea($estado)
    {
        $estados = [
            'B' => 'Pendiente',
            'P' => 'Esperando ser aprobada',
            'R' => 'Realizada',
            'C' => 'Cancelada',
        ];

        return $estados[$estado] ?? 'Desconocido';
    }
    /**
     * Renderiza una vista con un layout común
     */
    protected function renderView($titulo, $contenido, $role = 'admin')
    {
        // Determina la plantilla según el rol
        $plantilla = $role === 'admin'
            ? 'plantilla.home'  // Plantilla para administradores
            : 'plantilla.user'; // Plantilla para usuarios

        return view($plantilla, [
            'titulo' => $titulo,
            'cuerpo' => $contenido,
        ]);
    }
}
