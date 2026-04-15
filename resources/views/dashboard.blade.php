@extends('templates.master')
@section('content')
<!-- HERO -->
<section class="hero">
    <div>
        <h1 class="fw-bold">Bienvenido 👋</h1>
        <p class="text-muted">Administra programas, cursos y competencias fácilmente</p>

        <div class="mt-4">
            <a href="{{ url('/curso')}}" wire:navigate class="btn btn-outline-dark px-4">
                <i class="bi bi-book" ></i> Cursos
            </a>
        </div>
    </div>
</section>

<!-- ACCESOS RÁPIDOS -->
<div class="container mb-5">
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-center p-3">
                <i class="bi bi-diagram-3 fs-1 text-success"></i>
                <h6 class="mt-3">Compentencias</h6>
                <a href="{{ url('/competencia')}}" wire:navigate class="stretched-link"></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-center p-3">
                <i class="bi bi-journal-text fs-1 text-primary"></i>
                <h6 class="mt-3">Cursos</h6>
                <a href="{{ url('/curso')}}" wire:navigate class="stretched-link"></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-center p-3">
                <i class="bi bi-bullseye fs-1 text-danger"></i>
                <h6 class="mt-3">Objetivos</h6>
                <a href="{{ url('/objetivo')}}" wire:navigate class="stretched-link"></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-center p-3">
                <i class="bi bi-bar-chart fs-1 text-warning"></i>
                <h6 class="mt-3">Indicadores</h6>
                <a href="{{ url('/grafica')}}" wire:navigate class="stretched-link"></a>
            </div>
        </div>

    </div>
</div>

<!-- FOOTER -->
<footer class="text-center text-muted py-3">
    © 2026 Gestión Curricular
</footer>
@endsection