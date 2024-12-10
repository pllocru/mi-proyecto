@extends('layouts.admin') <!-- Extiende el layout principal -->

@section('title', 'Página de inicio') <!-- Sección para el título de la página -->

@section('menu')
<nav>
    <div>
        <ul>
            <!-- Usando route() para generar las URLs -->
            <li><a href="{{ route('tasks.list') }}">Ver tareas</a></li>
            <li><a href="{{ route('tasks.store') }}">Añadir tarea</a></li>
            <li><a href="{{ route('user.list') }}">Usuarios</a></li>
            <li><a href="{{ route('user.store') }}">Añadir usuarios</a></li>
            <li><a href="{{route('user.logout')}}">Log out</a></li>
        </ul>
    </div>
</nav>
@endsection

@section('cuerpo')
<div>
    {!! $cuerpo !!}
</div>
@endsection