<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareasCtrl;
use App\Http\Controllers\UserCtrl;

// Página de inicio, Redirige al listado o la vista inicial
//Route::get('/', [TareasCtrl::class, 'Listar'])->name('tasks.list');

// Página de listado de tareas
Route::get('/listar', [TareasCtrl::class, 'Listar'])->name('tasks.list');

// Página para ver una tarea específica
Route::get('/ver/{id}', [TareasCtrl::class, 'ver'])->name('tasks.ver');
// Ruta para mostrar la confirmación de borrar (GET)
Route::get('/borrar/{id}', [TareasCtrl::class, 'confirmarBorrar'])->name('tasks.borrar');
// Ruta para procesar la eliminación (POST)
Route::post('/borrar/{id}', [TareasCtrl::class, 'borrar'])->name('tasks.borrar');
// Página para añadir una nueva tarea
Route::get('/add', [TareasCtrl::class, 'add'])->name('tasks.add');
// Ruta para almacenar (crear) la nueva tarea
Route::post('/add', [TareasCtrl::class, 'store'])->name('tasks.store');
// Página para editar una tarea existente
Route::get('/edit/{id}', [TareasCtrl::class, 'Edit'])->name('tasks.edit');
// Ruta para almacenar (actualizar) la tarea editada
Route::post('/edit/{id}', [TareasCtrl::class, 'updateTarea'])->name('tasks.update');
// Página de inicio (otra ruta para redirigir al inicio)
Route::get('/inicio', [TareasCtrl::class, 'inicio'])->name('tasks.inicio');

Route::get('/listaroperario', [TareasCtrl::class, 'ListarOperario'])->name('operario.list');

//Route::post('/tasks/{id}/complete', [TareasCtrl::class, 'complete'])->name('tasks.complete');

Route::match(['get', 'post'], '/complete/{id}', [TareasCtrl::class, 'completeTask'])->name('tasks.complete');

Route::post('/vertask/{id}', [TareasCtrl::class, 'VerTarea'])->name('tasks.details');

Route::match(['get', 'post'], '/completetask/{id}', [TareasCtrl::class, 'markAsCompleted'])->name('tasks.markAsCompleted');




Route::get('/', [UserCtrl::class, 'login'])->name('user.login');
// Ruta para manejar el envío del formulario de inicio de sesión
Route::post('/', [UserCtrl::class, 'login'])->name('user.login.submit');
// Ruta para la página de listar para usuarios normales
Route::get('/listar2', [UserCtrl::class, 'Listar'])->name('user.list');
// Ruta para la página de inicio para usuarios normales
Route::get('/home', [UserCtrl::class, 'home'])->name('user.home');

Route::get('/ver2/{id}', [UserCtrl::class, 'ver'])->name('user.ver');

Route::get('/borrar2/{id}', [UserCtrl::class, 'confirmarBorrar'])->name('user.borrar');
// Ruta para procesar la eliminación (POST)
Route::post('/borrar2/{id}', [UserCtrl::class, 'borrar'])->name('user.borrar');

Route::get('/add2', [UserCtrl::class, 'addUsuario'])->name('user.add');
// Ruta para almacenar (crear) la nueva tarea
Route::post('/add2', [UserCtrl::class, 'storeUsuario'])->name('user.store');

Route::get('/edit2/{id}', [UserCtrl::class, 'editUsuario'])->name('user.edit');
// Ruta para almacenar (actualizar) la tarea editada
Route::post('/edit2/{id}', [UserCtrl::class, 'updateUsuario'])->name('user.update');

Route::get('/logout', [UserCtrl::class, 'logout'])->name('user.logout');






/**
 * Devuelve el valor de una variable enviada por POST. Devolverá el valor
 * por defecto en caso de no existir.
 *
 * @param string $campo
 * @param string $default   Valor por defecto en caso de no existir
 * @return string
 */
function VPost($campo, $default = '')
{
    if (isset($_POST[$campo])) {
        return $_POST[$campo];
    } else {
        return $default;
    }
}
