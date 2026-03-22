<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Servicio;
use App\Models\Animal;
use App\Models\Personal;
use App\Models\User;
use Carbon\Carbon;

class ServiciosSeeder extends Seeder
{
    public function run()
    {
        $hoy = Carbon::today();
        $finMes = Carbon::today()->endOfMonth();

        $categoriaMeta = $this->getColumnMeta('servicios', 'categoria_registro');
        $tipoServicioMeta = $this->getColumnMeta('servicios', 'tipo_servicio');
        $tipoBusquedaMeta = $this->getColumnMeta('servicios', 'tipo_busqueda');

        $categoriasPreferidas = ['SERVICIO', 'APOYO', 'MEMORANDUM'];
        $tiposServicioPreferidos = [
            'SEGURIDAD',
            'BARRIDOS DE SEGURIDAD',
            'BUSQUEDA',
            'DESFILES',
            'PROXIMIDAD SOCIAL',
            'ACTOS CIVICOS',
        ];
        $tiposBusquedaPreferidos = [
            'EN VIDA',
            'RECURSO HUMANO',
            'EXPLOSIVO',
            'FORENSE',
            'NARCOTICOS',
        ];

        $categoriasRegistro = $this->resolveAllowedValues($categoriaMeta, $categoriasPreferidas);
        $tiposServicio = $this->resolveAllowedValues($tipoServicioMeta, $tiposServicioPreferidos);
        $tiposBusqueda = $this->resolveAllowedValues($tipoBusquedaMeta, $tiposBusquedaPreferidos);

        if (empty($categoriasRegistro)) {
            $categoriasRegistro = ['SERVICIO'];
        }

        if (empty($tiposServicio)) {
            $tiposServicio = ['SEGURIDAD'];
        }

        $personales = Personal::where('activo', 1)->pluck('id')->toArray();
        $caninos = Animal::where('tipo', 'CANINO')->pluck('id')->toArray();
        $equinos = Animal::where('tipo', 'EQUINO')->pluck('id')->toArray();
        $usuarios = User::pluck('id')->toArray();

        $patrullas = [];
        if (DB::getSchemaBuilder()->hasTable('patrols')) {
            $patrullas = DB::table('patrols')->pluck('id')->toArray();
        } elseif (DB::getSchemaBuilder()->hasTable('patrullas')) {
            $patrullas = DB::table('patrullas')->pluck('id')->toArray();
        }

        $fecha = $hoy->copy();

        while ($fecha->lte($finMes)) {
            $cantidad = rand(2, 5);

            for ($i = 0; $i < $cantidad; $i++) {
                $tipoServicio = $tiposServicio[array_rand($tiposServicio)];
                $categoriaRegistro = $categoriasRegistro[array_rand($categoriasRegistro)];

                $esBusqueda = stripos($tipoServicio, 'BUSQ') !== false;
                $esSeguridad = stripos($tipoServicio, 'SEGUR') !== false;
                $esBarrido = stripos($tipoServicio, 'BARR') !== false;
                $esDesfile = stripos($tipoServicio, 'DESFIL') !== false;
                $esProximidad = stripos($tipoServicio, 'PROX') !== false;
                $esActoCivico = stripos($tipoServicio, 'CIVIC') !== false || stripos($tipoServicio, 'ACTO') !== false;

                Servicio::create([
                    'created_by' => !empty($usuarios) ? $usuarios[array_rand($usuarios)] : 1,
                    'personal_id' => !empty($personales) ? $personales[array_rand($personales)] : null,
                    'canino_id' => !empty($caninos) ? $caninos[array_rand($caninos)] : null,
                    'equino_id' => !empty($equinos) ? $equinos[array_rand($equinos)] : null,
                    'patrulla_id' => !empty($patrullas) ? $patrullas[array_rand($patrullas)] : null,

                    'categoria_registro' => $categoriaRegistro,
                    'tipo_servicio' => $tipoServicio,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora' => Carbon::createFromTime(rand(6, 22), rand(0, 59), 0)->format('H:i:s'),
                    'cumplio' => rand(0, 1),

                    'seguridad' => $esSeguridad ? 1 : 0,
                    'barrido_seguridad' => $esBarrido ? 1 : 0,
                    'desfiles' => $esDesfile ? 1 : 0,
                    'proximidad_social' => $esProximidad ? 1 : 0,
                    'actos_civicos' => $esActoCivico ? 1 : 0,

                    'tipo_busqueda' => ($esBusqueda && !empty($tiposBusqueda))
                        ? $tiposBusqueda[array_rand($tiposBusqueda)]
                        : null,

                    'asunto' => $this->fitText(
                        $this->generarAsunto($categoriaRegistro, $tipoServicio),
                        $this->getVarcharLength('servicios', 'asunto')
                    ),
                    'lugar' => $this->fitText(
                        $this->generarLugar(),
                        $this->getVarcharLength('servicios', 'lugar')
                    ),
                    'descripcion' => $this->fitText(
                        $this->generarDescripcion(),
                        $this->getVarcharLength('servicios', 'descripcion')
                    ),
                    'observaciones' => $this->fitText(
                        $this->generarObservacion(),
                        $this->getVarcharLength('servicios', 'observaciones')
                    ),

                    'archivo' => null,
                    'archivo_nombre_original' => null,
                    'archivo_mime' => null,
                    'archivo_size' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $fecha->addDay();
        }
    }

    private function getColumnMeta($table, $column)
    {
        $table = str_replace('`', '', $table);
        $column = str_replace("'", "''", $column);

        $rows = DB::select("SHOW COLUMNS FROM `{$table}` WHERE `Field` = '{$column}'");

        if (empty($rows)) {
            return null;
        }

        $row = $rows[0];

        return [
            'type' => isset($row->Type) ? $row->Type : '',
            'null' => isset($row->Null) ? $row->Null : '',
            'key' => isset($row->Key) ? $row->Key : '',
            'default' => isset($row->Default) ? $row->Default : null,
            'extra' => isset($row->Extra) ? $row->Extra : '',
        ];
    }

    private function getEnumValues($type)
    {
        if (!preg_match("/^enum\((.*)\)$/i", $type, $matches)) {
            return [];
        }

        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'/", $matches[1], $vals);

        return array_map(function ($v) {
            return str_replace("\\'", "'", $v);
        }, $vals[1]);
    }

    private function getVarcharLength($table, $column)
    {
        $meta = $this->getColumnMeta($table, $column);

        if (!$meta || empty($meta['type'])) {
            return null;
        }

        if (preg_match('/varchar\((\d+)\)/i', $meta['type'], $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private function resolveAllowedValues($meta, array $preferidos)
    {
        if (!$meta || empty($meta['type'])) {
            return $preferidos;
        }

        $enumValues = $this->getEnumValues($meta['type']);
        if (!empty($enumValues)) {
            return $enumValues;
        }

        if (preg_match('/varchar\((\d+)\)/i', $meta['type'], $m)) {
            $max = (int) $m[1];

            $filtrados = array_values(array_filter($preferidos, function ($v) use ($max) {
                return mb_strlen($v, 'UTF-8') <= $max;
            }));

            if (!empty($filtrados)) {
                return $filtrados;
            }

            return [mb_substr($preferidos[0], 0, $max, 'UTF-8')];
        }

        return $preferidos;
    }

    private function fitText($text, $max)
    {
        if (!$max) {
            return $text;
        }

        return mb_substr($text, 0, $max, 'UTF-8');
    }

    private function generarAsunto($categoria, $tipo)
    {
        return $categoria . ' DE ' . $tipo;
    }

    private function generarLugar()
    {
        $lugares = [
            'CENTRO HISTORICO DE MORELIA',
            'PLAZA DE ARMAS',
            'AVENIDA MADERO',
            'INSTALACIONES DEL AGRUPAMIENTO',
            'EXPLANADA PRINCIPAL',
            'COLONIA VILLA UNIVERSIDAD',
            'TENENCIA MORELOS',
            'SALIDA A PATZCUARO',
        ];

        return $lugares[array_rand($lugares)];
    }

    private function generarDescripcion()
    {
        return 'Registro operativo generado para pruebas del modulo de servicios.';
    }

    private function generarObservacion()
    {
        return 'Dato de prueba generado automaticamente para revision visual.';
    }
}
