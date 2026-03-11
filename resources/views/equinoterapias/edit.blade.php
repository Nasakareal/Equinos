@extends('adminlte::page')

@section('title', 'Editar Equinoterapia')

@section('content_header')
    <h1>Editar Equinoterapia</h1>
@stop

@section('content')

<form action="{{ route('equinoterapias.update', $equinoterapia->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-12">

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h3 class="card-title">Datos generales del reporte</h3>

                    <div class="card-tools">
                        <a href="{{ route('equinoterapias.show', $equinoterapia->id) }}" class="btn btn-info btn-sm">
                            <i class="fa-regular fa-eye"></i> Ver
                        </a>

                        <a href="{{ route('equinoterapias.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date"
                                       name="fecha"
                                       class="form-control @error('fecha') is-invalid @enderror"
                                       value="{{ old('fecha', \Carbon\Carbon::parse($equinoterapia->fecha)->format('Y-m-d')) }}"
                                       required>
                                @error('fecha')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Personal</label>
                                <input type="number"
                                       name="personal"
                                       class="form-control"
                                       min="0"
                                       value="{{ old('personal', $equinoterapia->personal) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Equinos</label>
                                <input type="number"
                                       name="equinos"
                                       class="form-control"
                                       min="0"
                                       value="{{ old('equinos', $equinoterapia->equinos) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valoraciones</label>
                                <input type="number"
                                       name="valoraciones"
                                       id="valoraciones"
                                       class="form-control"
                                       min="0"
                                       value="{{ old('valoraciones', $equinoterapia->valoraciones) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Actividades del área</label>
                                <textarea name="actividades_area"
                                          class="form-control"
                                          rows="3">{{ old('actividades_area', $equinoterapia->actividades_area) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones"
                                          class="form-control"
                                          rows="3">{{ old('observaciones', $equinoterapia->observaciones) }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="row text-center mt-2">

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3 id="total_terapias">0</h3>
                                    <p>Terapias</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-horse"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3 id="total_inasistencias">0</h3>
                                    <p>Inasistencias</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-pink">
                                <div class="inner">
                                    <h3 id="total_ninas">0</h3>
                                    <p>Niñas</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-child-dress"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 id="total_ninos">0</h3>
                                    <p>Niños</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-child"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3 id="total_valoraciones">0</h3>
                                    <p>Valoraciones</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-notes-medical"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 id="total_registros">0</h3>
                                    <p>Registros</p>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h3 class="card-title">Registros</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-pink btn-sm" id="btn-agregar-fila">
                            <i class="fa-solid fa-plus"></i> Agregar registro
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre completo</th>
                                    <th>Sexo</th>
                                    <th>Diagnóstico</th>
                                    <th>Asistencia</th>
                                    <th>Valoración</th>
                                    <th>Motivo inasistencia</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>

                            <tbody id="contenedor-registros">

                                @foreach($equinoterapia->registros as $registro)

                                <tr class="fila-registro">
                                    <td class="fila-numero"></td>

                                    <td>
                                        <input type="text"
                                               name="nombre_completo[]"
                                               class="form-control"
                                               value="{{ $registro->nombre_completo }}">
                                    </td>

                                    <td>
                                        <select name="sexo[]" class="form-control select-sexo">
                                            <option value="NIÑO" {{ $registro->sexo == 'NIÑO' ? 'selected' : '' }}>NIÑO</option>
                                            <option value="NIÑA" {{ $registro->sexo == 'NIÑA' ? 'selected' : '' }}>NIÑA</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text"
                                               name="diagnostico[]"
                                               class="form-control"
                                               value="{{ $registro->diagnostico }}">
                                    </td>

                                    <td>
                                        <select name="estatus_asistencia[]" class="form-control select-asistencia">
                                            <option value="ASISTIO" {{ $registro->estatus_asistencia == 'ASISTIO' ? 'selected' : '' }}>ASISTIÓ</option>
                                            <option value="INASISTIO" {{ $registro->estatus_asistencia == 'INASISTIO' ? 'selected' : '' }}>INASISTIÓ</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="es_valoracion[]" class="form-control select-valoracion">
                                            <option value="0" {{ !$registro->es_valoracion ? 'selected' : '' }}>NO</option>
                                            <option value="1" {{ $registro->es_valoracion ? 'selected' : '' }}>SÍ</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text"
                                               name="motivo_inasistencia[]"
                                               class="form-control input-motivo"
                                               value="{{ $registro->motivo_inasistencia }}"
                                               {{ $registro->estatus_asistencia == 'ASISTIO' ? 'disabled' : '' }}>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn-quitar-fila">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-floppy-disk"></i> Actualizar
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

</form>

@stop

@section('css')
<style>
.table th, .table td{
    text-align:center;
    vertical-align:middle;
}

.bg-pink{
    background-color:#e83e8c !important;
    color:#fff !important;
}

.btn-pink{
    background:#e83e8c !important;
    border:#e83e8c !important;
    color:#fff !important;
}

.card-outline.card-pink{
    border-top:3px solid #e83e8c;
}

.small-box .icon i{
    font-size:50px;
    top:12px;
}
</style>
@stop

@section('js')
<script>

function actualizarNumerosFilas(){
    $('#contenedor-registros .fila-registro').each(function(index){
        $(this).find('.fila-numero').text(index+1);
    });
}

function actualizarEstadoMotivo(){
    $('#contenedor-registros .fila-registro').each(function(){

        let asistencia=$(this).find('.select-asistencia').val();
        let motivo=$(this).find('.input-motivo');

        if(asistencia==='INASISTIO'){
            motivo.prop('disabled',false);
        }else{
            motivo.prop('disabled',true);
            motivo.val('');
        }

    });
}

function actualizarResumen(){

    let terapias=0;
    let inasistencias=0;
    let ninas=0;
    let ninos=0;
    let valoraciones=0;
    let registros=0;

    $('#contenedor-registros .fila-registro').each(function(){

        let nombre=$(this).find('input[name="nombre_completo[]"]').val().trim();
        let sexo=$(this).find('.select-sexo').val();
        let asistencia=$(this).find('.select-asistencia').val();
        let esValoracion=$(this).find('.select-valoracion').val();

        if(nombre!==''){
            registros++;
        }

        if(nombre!=='' && asistencia==='INASISTIO'){
            inasistencias++;
        }

        if(nombre!=='' && asistencia==='ASISTIO' && esValoracion==='0'){
            terapias++;
        }

        if(nombre!=='' && asistencia==='ASISTIO' && sexo==='NIÑA'){
            ninas++;
        }

        if(nombre!=='' && asistencia==='ASISTIO' && sexo==='NIÑO'){
            ninos++;
        }

        if(nombre!=='' && esValoracion==='1'){
            valoraciones++;
        }

    });

    $('#total_terapias').text(terapias);
    $('#total_inasistencias').text(inasistencias);
    $('#total_ninas').text(ninas);
    $('#total_ninos').text(ninos);
    $('#total_valoraciones').text(valoraciones);
    $('#total_registros').text(registros);

    $('#valoraciones').val(valoraciones);

}

function crearFila(){

return `
<tr class="fila-registro">
<td class="fila-numero"></td>

<td>
<input type="text" name="nombre_completo[]" class="form-control">
</td>

<td>
<select name="sexo[]" class="form-control select-sexo">
<option value="NIÑO">NIÑO</option>
<option value="NIÑA">NIÑA</option>
</select>
</td>

<td>
<input type="text" name="diagnostico[]" class="form-control">
</td>

<td>
<select name="estatus_asistencia[]" class="form-control select-asistencia">
<option value="ASISTIO">ASISTIÓ</option>
<option value="INASISTIO">INASISTIÓ</option>
</select>
</td>

<td>
<select name="es_valoracion[]" class="form-control select-valoracion">
<option value="0">NO</option>
<option value="1">SÍ</option>
</select>
</td>

<td>
<input type="text" name="motivo_inasistencia[]" class="form-control input-motivo" disabled>
</td>

<td>
<button type="button" class="btn btn-danger btn-sm btn-quitar-fila">
<i class="fa-solid fa-trash"></i>
</button>
</td>

</tr>
`;

}

$(function(){

actualizarNumerosFilas();
actualizarEstadoMotivo();
actualizarResumen();

$('#btn-agregar-fila').on('click',function(){

$('#contenedor-registros').append(crearFila());

actualizarNumerosFilas();
actualizarEstadoMotivo();
actualizarResumen();

});

$(document).on('click','.btn-quitar-fila',function(){

if($('#contenedor-registros .fila-registro').length>1){

$(this).closest('tr').remove();

actualizarNumerosFilas();
actualizarEstadoMotivo();
actualizarResumen();

}

});

$(document).on('change keyup','.select-sexo,.select-asistencia,.select-valoracion,input[name="nombre_completo[]"],input[name="diagnostico[]"],.input-motivo',function(){

actualizarEstadoMotivo();
actualizarResumen();

});

});

</script>
@stop
