@extends('layouts.user')

@section('title', 'Panel de Usuario')

@section('menu')
<nav>
    <div>
        <ul>
            <li><a href="{{ route('operario.list') }}">Mis tareas</a></li>
            <li><a href="{{ route('user.logout') }}">Log out</a></li>
        </ul>
    </div>
</nav>
@endsection

@section('cuerpo')
<div>
    {!! $cuerpo !!}
</div>
@endsection