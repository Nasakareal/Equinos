@extends('adminlte::page')

@section('title', 'Horario Personal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Horario de {{ $personal->nombres }}</h1>
        <div>
            <a href="{{ route('personal.show', $personal->id) }}" class="btn btn-info">
                <i class="fa-regular fa-eye"></i> Ver perfil
            </a>
            <a href="{{ route('personal.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa-regular fa-clock"></i> Configurar horario (turno MIXTO / personalizado)
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Área</div>
                            <div class="font-weight-bold">{{ $personal->area->nombre ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Turno</div>
                            <div class="font-weight-bold">{{ $personal->turno->nombre ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Dependencia</div>
                            <div class="font-weight-bold">{{ $personal->dependencia ?? '-' }}</div>
                        </div>
                    </div>

                    @php
                        $dias = [
                            0 => 'Lunes',
                            1 => 'Martes',
                            2 => 'Miércoles',
                            3 => 'Jueves',
                            4 => 'Viernes',
                            5 => 'Sábado',
                            6 => 'Domingo',
                        ];

                        $horariosPorDia = [];

                        if (isset($horario) && isset($horario->detalles) && is_iterable($horario->detalles)) {
                            foreach ($horario->detalles as $h) {
                                $k = (int)($h->dia_semana ?? -1);
                                if (!isset($horariosPorDia[$k])) $horariosPorDia[$k] = [];
                                $horariosPorDia[$k][] = $h;
                            }
                        }

                        foreach ($horariosPorDia as $k => $arr) {
                            usort($arr, function ($a, $b) {
                                return strcmp((string)($a->hora_entrada ?? ''), (string)($b->hora_entrada ?? ''));
                            });
                            $horariosPorDia[$k] = $arr;
                        }

                        $bloques = ['A', 'B', 'C', 'D', 'E', 'F', 'GENERAL'];
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 140px; text-align:center;">Día</th>
                                    <th>Tramos</th>
                                    <th style="width: 460px; text-align:center;">Agregar tramo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dias as $diaKey => $diaLabel)
                                    @php
                                        $tramos = $horariosPorDia[$diaKey] ?? [];
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold align-middle">{{ $diaLabel }}</td>

                                        <td class="align-middle">
                                            @if (count($tramos) === 0)
                                                <span class="badge badge-secondary">Sin tramos</span>
                                            @else
                                                <div class="d-flex flex-wrap" style="gap:10px;">
                                                    @foreach ($tramos as $t)
                                                        @php
                                                            $hi = substr((string)($t->hora_entrada ?? ''), 0, 5);
                                                            $hf = substr((string)($t->hora_salida ?? ''), 0, 5);
                                                            $tb = trim((string)($t->bloque ?? ''));
                                                        @endphp
                                                        <div class="border rounded px-2 py-1">
                                                            <div class="font-weight-bold">
                                                                {{ $hi }} - {{ $hf }}
                                                                @if (!empty($t->cruza_dia))
                                                                    <span class="badge badge-warning">Cruza día</span>
                                                                @endif
                                                                @if ($tb !== '')
                                                                    <span class="badge badge-info">{{ $tb }}</span>
                                                                @endif
                                                            </div>

                                                            @if (!empty($t->notas))
                                                                <div class="text-muted small">{{ $t->notas }}</div>
                                                            @endif

                                                            <div class="mt-2 d-flex" style="gap:6px;">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-success btn-edit-tramo"
                                                                        data-id="{{ $t->id }}"
                                                                        data-dia_semana="{{ (int)$diaKey }}"
                                                                        data-inicio="{{ $hi }}"
                                                                        data-fin="{{ $hf }}"
                                                                        data-cruza="{{ (int)($t->cruza_dia ?? 0) }}"
                                                                        data-notas="{{ $t->notas ?? '' }}"
                                                                        data-bloque="{{ $tb }}">
                                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                                </button>

                                                                <form action="{{ route('personal.horario_detalles.destroy', [$personal->id, $horario->id, $t->id]) }}"
                                                                      method="POST"
                                                                      class="form-delete-tramo d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-tramo">
                                                                        <i class="fa-regular fa-trash-can"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <form action="{{ route('personal.horario_detalles.store', [$personal->id, $horario->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="dia_semana" value="{{ (int)$diaKey }}">

                                                <div class="form-row">
                                                    <div class="col">
                                                        <input type="time"
                                                               name="hora_inicio"
                                                               class="form-control form-control-sm @error('hora_inicio') is-invalid @enderror"
                                                               value="{{ old('hora_inicio') }}"
                                                               required>
                                                    </div>

                                                    <div class="col">
                                                        <input type="time"
                                                               name="hora_fin"
                                                               class="form-control form-control-sm @error('hora_fin') is-invalid @enderror"
                                                               value="{{ old('hora_fin') }}"
                                                               required>
                                                    </div>

                                                    <div class="col-auto d-flex align-items-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                   class="custom-control-input"
                                                                   id="cruza_{{ $diaKey }}"
                                                                   name="cruza_dia"
                                                                   value="1"
                                                                   {{ old('cruza_dia') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="cruza_{{ $diaKey }}">Cruza día</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-row mt-2">
                                                    <div class="col">
                                                        <select name="bloque" class="form-control form-control-sm @error('bloque') is-invalid @enderror" required>
                                                            @foreach ($bloques as $b)
                                                                <option value="{{ $b }}" {{ old('bloque', 'A') === $b ? 'selected' : '' }}>{{ $b }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col">
                                                        <input type="text"
                                                               name="notas"
                                                               class="form-control form-control-sm @error('notas') is-invalid @enderror"
                                                               value="{{ old('notas') }}"
                                                               placeholder="Notas (opcional)">
                                                    </div>

                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fa-solid fa-plus"></i> Agregar
                                                        </button>
                                                    </div>
                                                </div>

                                                @error('dia_semana')
                                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                                @enderror
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <div class="font-weight-bold mb-2">Errores</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditTramo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditTramo" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-regular fa-clock"></i> Editar tramo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="dia_semana" id="edit_dia_semana">

                        <div class="form-group">
                            <label for="edit_hora_inicio">Hora inicio</label>
                            <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_hora_fin">Hora fin</label>
                            <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="edit_cruza_dia" name="cruza_dia" value="1">
                                <label class="custom-control-label" for="edit_cruza_dia">Cruza día</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_bloque">Bloque</label>
                            <select name="bloque" id="edit_bloque" class="form-control" required>
                                @foreach ($bloques as $b)
                                    <option value="{{ $b }}">{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_notas">Notas</label>
                            <input type="text" name="notas" id="edit_notas" class="form-control" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa-solid fa-ban"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td { vertical-align: middle; }
    </style>
@stop

@section('js')
    <script>
        (function () {
            $(document).on('click', '.btn-delete-tramo', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: '¿Eliminar tramo?',
                    text: 'Esta acción no se puede revertir.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            $(document).on('click', '.btn-edit-tramo', function () {
                const tramoId = $(this).data('id');
                const diaSemana = parseInt($(this).data('dia_semana'), 10);
                const inicio = $(this).data('inicio');
                const fin = $(this).data('fin');
                const cruza = parseInt($(this).data('cruza') || 0, 10);
                const notas = $(this).data('notas') || '';
                const bloque = ($(this).data('bloque') || 'A').toString();

                $('#edit_dia_semana').val(diaSemana);
                $('#edit_hora_inicio').val(inicio);
                $('#edit_hora_fin').val(fin);
                $('#edit_cruza_dia').prop('checked', cruza === 1);
                $('#edit_notas').val(notas);
                $('#edit_bloque').val(bloque);

                const url = "{{ route('personal.horario_detalles.update', [$personal->id, $horario->id, 'DETALLE_ID']) }}".replace('DETALLE_ID', tramoId);
                $('#formEditTramo').attr('action', url);

                $('#modalEditTramo').modal('show');
            });

            @if (session('success'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 9000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif
        })();
    </script>
@stop
