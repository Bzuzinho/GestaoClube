@extends('layouts.app')

@section('title', 'Início')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Bem-vindo à página principal</h2>
    @auth
        <p>Autenticado como: {{ Auth::user()->name }}</p>
    @else
        <p>Não estás autenticado.</p>
    @endauth
@endsection
