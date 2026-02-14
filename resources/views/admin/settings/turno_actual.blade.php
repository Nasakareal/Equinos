@extends('adminlte::page')

@section('title', 'Turno en servicio')

@section('content_header')
    <h1>Turno en servicio</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('settings.turno_actual.update') }}">
            @csrf

            <div class="form-group">
                <label>Turno que está laborando</label>
                <select name="turno_actual_id" class="form-control" required>
                    @foreach($turnos as $t)
                        <option value="{{ $t->id }}" {{ (int)$turno_actual_id === (int)$t->id ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary mt-2">Guardar</button>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
    select.form-control{
        background: rgba(0,0,0,.22) !important;
        color: rgba(234,240,255,.95) !important;
        border: 1px solid rgba(255,255,255,.18) !important;
    }

    select.form-control:focus{
        border-color: rgba(45,168,255,.55) !important;
        box-shadow: 0 0 0 .2rem rgba(45,168,255,.18) !important;
        outline: none !important;
    }

    select.form-control option{
        background: #ffffff !important;
        color: #111111 !important;
    }
</style>
@stop
