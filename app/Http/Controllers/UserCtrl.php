<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\GestorErrores;
use App\Models\Paginador;

include_once realpath(__DIR__ . '/../../../helpers/Funciones.php');
class UserCtrl
{
    private $userModel;

    protected $errores = null;

    public function __construct()
    {

        // Iniciamos el modelo de usuarios
        $this->userModel = new UserModel();

        $this->errores = new GestorErrores(
            '<span style="color:red; background:#EEE; padding:.2em 1em; margin:1em">',
            '</span>'
        );
    }

    // Acción para mostrar el formulario de inicio de sesión y procesarlo
    public function login(Request $request)
    {
        // AutoLogin: Verificar si las cookies están configuradas y no están vacías
        if (empty($_SESSION['role']) && !empty($_COOKIE['role']) && !empty($_COOKIE['user'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start(); // Iniciar sesión si no está ya iniciada
            }

            // Configurar la sesión desde las cookies
            $_SESSION['role'] = htmlspecialchars($_COOKIE['role']); // Escapar por seguridad
            $_SESSION['user'] = htmlspecialchars($_COOKIE['user']);

            // Preparar el contenido para la vista según el rol
            $titulo = 'Panel de ' . ucfirst($_SESSION['role']);
            $contenido = '<p>Bienvenido de nuevo al panel de ' . $_SESSION['role'] . '.</p>';

            return $this->renderView($titulo, $contenido);
        }

        // Proceso de login normal
        if ($request->isMethod('post')) {
            $username = $request->input('username');
            $password = $request->input('password');

            if (empty($username) || empty($password)) {
                return view('login', ['error' => 'Todos los campos son obligatorios.']);
            }

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Configurar sesión
                $_SESSION['role'] = $user['role'];
                $_SESSION['user'] = $user['name'];

                // Configurar cookies si el usuario seleccionó "Mantener sesión iniciada"
                if ($request->has('remember_me')) {
                    setcookie('role', $user['role'], time() + (86400 * 30), "/", "", false, true); // 30 días
                    setcookie('user', $user['name'], time() + (86400 * 30), "/", "", false, true); // 30 días
                }

                $titulo = 'Panel de ' . ucfirst($user['role']);
                $contenido = '<p>Bienvenido al panel de ' . $user['role'] . '.</p>';

                return $this->renderView($titulo, $contenido);
            } else {
                return view('login', ['error' => 'Usuario o contraseña incorrectos.']);
            }
        }

        // Si no es POST y no hay cookies válidas, mostrar la página de login
        return view('login');
    }



    public function logout()
    {

        // Eliminar cookies
        setcookie('role', '', time() - 3600, "/");
        setcookie('user', '', time() - 3600, "/");

        return redirect()->route('user.login');
    }



    public function Listar()
    {
        // Obtiene el número de página actual desde la URL, por defecto es la página 1
        $page = $_GET['page'] ?? 1;

        // Obtiene todos los usuarios
        $usuarios = $this->userModel->getAllUsers();

        // Instancia el paginador (10 usuarios por página)
        $paginador = new Paginador($usuarios, 10, $page);

        // Obtiene los resultados de la página actual
        $usuariosPaginados = $paginador->getResults();

        // Genera los enlaces de paginación
        $paginationLinks = $paginador->getPaginationLinks(route('user.list')); // Asegúrate de pasar la URL base

        // Renderiza la vista con los usuarios paginados
        return $this->renderView('Listado de usuarios', view('user.listar', [
            'usuarios' => $usuariosPaginados,
            'paginationLinks' => $paginationLinks,
            'page' => $page, // Pasamos el número de página a la vista
        ]));
    }

    public function Ver($id)
    {
        // Obtiene el usuario por ID
        $usuario = $this->userModel->getUserById($id);

        // Si el usuario no existe
        if (!$usuario) {
            return $this->renderView('Error', view('error', ['mensaje' => 'Usuario no encontrado']));
        }

        // Obtener el número de página de la URL (si existe)
        $page = $_GET['page'] ?? 1; // Si no existe, asumir la página 1

        // Pasar el usuario y el número de página actual a la vista
        return $this->renderView('Detalles del Usuario', view('user.ver', [
            'usuario' => $usuario,
            'page' => $page // Pasar el número de página
        ]));
    }

    public function confirmarBorrar($id)
    {
        // Obtener el usuario por ID
        $usuario = $this->userModel->getUserById($id);

        if (!$usuario) {
            return $this->renderView('Error', view('error', ['mensaje' => 'Usuario no encontrado']));
        }

        // Mostrar la vista de confirmación con el usuario
        return $this->renderView('Confirmar Borrar', view('user.borrar', [
            'usuario' => $usuario,
        ]));
    }

    // Método para eliminar el usuario
    public function borrar($id)
    {
        $usuario = $this->userModel->getUserById($id);

        if ($usuario) {
            // Eliminar el usuario
            $this->userModel->deleteUser($id);

            // Obtener el número de página de la URL, si está disponible
            $page = $_GET['page'] ?? 1;

            // Realizar la redirección a la URL '/listar' con la página actual
            header('Location: ' . route('user.list') . '?page=' . $page);
            exit(); // Asegurarse de que el script termine después de la redirección
        } else {
            // Si no existe el usuario, mostrar error
            return $this->renderView('Error', view('error', ['mensaje' => 'Usuario no encontrado']));
        }
    }


    public function addUsuario()
    {
        // Renderiza la vista para añadir un usuario
        return $this->renderView('Añadir Usuario', view('user.add', []));
    }

    public function storeUsuario()
    {
        // Verifica si se envió el formulario
        if ($_POST) {
            // Filtrar y validar los campos del formulario
            $this->FiltraCamposPostUsuario();

            // Si no hay errores, proceder a guardar el usuario
            if (!$this->errores->hayErrores()) {
                // Obtener los datos del formulario
                $usuario = [
                    'name' => trim(VPost('name')),
                    'password' => password_hash(trim(VPost('password')), PASSWORD_BCRYPT), // Hashear la contraseña
                    'role' => VPost('role'),
                ];

                // Llamar al modelo para guardar el usuario
                $lastInsertId = $this->userModel->addUser($usuario);

                if ($lastInsertId) {
                    // Redirigir al listado con un mensaje de éxito
                    header('Location: ' . route('user.list'));
                    exit();
                } else {
                    // Mostrar un mensaje de error si no se pudo guardar
                    return $this->renderView('Error', view('user.add', [
                        'errores' => ['error' => 'No se pudo crear el usuario.'],
                    ]));
                }
            }

            // Si hay errores, regresar al formulario con los errores y datos ingresados
            return $this->renderView('Errores', view('user.add', [
                'usuario' => $_POST,
                'errores' => $this->errores,
            ]));
        }

        // Si el formulario no se ha enviado, cargar la vista del formulario vacío
        return $this->renderView('Añadir Usuario', view('user.add', []));
    }


    protected function FiltraCamposPostUsuario()
    {
        // Obtener y limpiar los valores del formulario
        $name = trim(VPost('name'));
        $password = trim(VPost('password'));
        $role = VPost('role');

        // Validar los datos de POST
        if ($name === '') {
            $this->errores->AnotaError('name', 'El campo Nombre es obligatorio.');
        }

        if ($password === '') {
            $this->errores->AnotaError('password', 'El campo Contraseña es obligatorio.');
        } elseif (strlen($password) < 8) {
            $this->errores->AnotaError('password', 'La contraseña debe tener al menos 8 caracteres.');
        }

        if ($role === '') {
            $this->errores->AnotaError('role', 'El campo Rol es obligatorio.');
        } elseif (!in_array($role, ['admin', 'user'])) {
            $this->errores->AnotaError('role', 'El rol seleccionado no es válido.');
        }
    }


    public function updateUsuario($id)
    {
        // Recoger los datos del formulario
        $data = $_POST;

        // Eliminar el token CSRF
        unset($data['_token']);

        // Si la contraseña está vacía, no actualizarla
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Si hay contraseña, encriptarla antes de guardar
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Actualizar el usuario con los nuevos datos
        $updated = $this->userModel->updateUser($id, $data);

        if ($updated) {
            // Redirigir con un mensaje de éxito
            header('Location: ' . route('user.list'));
            exit();
        } else {
            // Redirigir con un mensaje de error
            header('Location: ' . route('user.edit', ['id' => $id]));
            exit();
        }
    }

    public function editUsuario($id)
    {
        // Obtener el usuario existente
        $usuario = $this->userModel->getUserById($id);

        // Si no existe el usuario, mostrar error
        if (!$usuario) {
            return $this->renderView('Error', view('error', ['mensaje' => 'No se encontró el usuario.']));
        }

        return $this->renderView('Editar Usuario', view('user.edit', [
            'usuario' => $usuario,
            'errores' => $this->errores,
        ]));
    }


    // Acción para mostrar la vista de un usuario normal
    public function home()
    {
        return view('home');
    }

    protected function verPlantilla()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Devuelve la plantilla según el rol
        return $_SESSION['role'] === 'admin'
            ? 'plantilla.home' // Plantilla para administrador
            : 'plantilla.user'; // Plantilla para usuario
    }


    protected function renderView($titulo, $contenido)
    {
        // Obtiene la plantilla correcta según el rol
        $plantilla = $this->verPlantilla();

        return view($plantilla, [
            'titulo' => $titulo,
            'cuerpo' => $contenido,
        ]);
    }
}
