@extends('adminlte::page')

@section('title', 'Crear Responsable')

@section('content_header')
    <h1>Crear Responsable</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Nuevo Responsable</h3>
                </div>

                <div class="card-body">

                    <form action="{{ route('responsables.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Personal</label>
                                    <select name="personal_id" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($personals as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('personal_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nivel</label>
                                    <select name="nivel" id="nivel" class="form-control" required>
                                        <option value="GENERAL" {{ old('nivel') == 'GENERAL' ? 'selected' : '' }}>
                                            GENERAL
                                        </option>
                                        <option value="AREA" {{ old('nivel') == 'AREA' ? 'selected' : '' }}>
                                            ÁREA
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Área</label>
                                    <select name="area_id" id="area_id" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @foreach ($areas as $a)
                                            <option value="{{ $a->id }}"
                                                {{ old('area_id') == $a->id ? 'selected' : '' }}>
                                                {{ $a->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group mt-2">
                            <label>
                                <input type="checkbox" name="activo" value="1"
                                    {{ old('activo', true) ? 'checked' : '' }}>
                                Responsable Activo
                            </label>
                        </div>

                        <hr>

                        <div class="form-group">
                            <a href="{{ route('responsables.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Cancelar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Guardar Responsable
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
@stop

@section('js')
<script>
    function toggleArea() {
        let nivel = document.getElementById("nivel").value;
        let areaSelect = document.getElementById("area_id");

        if (nivel === "GENERAL") {
            areaSelect.value = "";
            areaSelect.disabled = true;
        } else {
            areaSelect.disabled = false;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        toggleArea();
        document.getElementById("nivel").addEventListener("change", toggleArea);
    });
</script>
@stop
