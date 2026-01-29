<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Personal;

class WeaponsFromPersonalSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('weapons') || !Schema::hasTable('weapon_assignments')) {
            if ($this->command) {
                $this->command->warn("Saltado: faltan tablas weapons/weapon_assignments.");
            }
            return;
        }

        $personals = Personal::query()
            ->select(['id', 'observaciones'])
            ->get();

        foreach ($personals as $p) {
            $obs = (string) ($p->observaciones ?? '');

            list($corta, $larga) = $this->extractMatriculas($obs);

            if (empty($corta) && empty($larga)) {
                continue;
            }

            foreach ($corta as $matricula) {
                $weaponId = $this->upsertWeapon('CORTA', $matricula);
                $this->upsertAssignment($p->id, $weaponId, $obs);
            }

            foreach ($larga as $matricula) {
                $weaponId = $this->upsertWeapon('LARGA', $matricula);
                $this->upsertAssignment($p->id, $weaponId, $obs);
            }
        }
    }

    private function upsertWeapon(string $tipo, string $matricula): int
    {
        $now = now();
        $matricula = strtoupper(trim($matricula));

        $existing = DB::table('weapons')
            ->where('matricula', $matricula)
            ->first();

        if ($existing) {
            DB::table('weapons')
                ->where('id', $existing->id)
                ->update([
                    'tipo' => $tipo,
                    'updated_at' => $now,
                ]);

            return (int) $existing->id;
        }

        return (int) DB::table('weapons')->insertGetId([
            'tipo' => $tipo,
            'marca_modelo' => null,
            'matricula' => $matricula,
            'estado' => 'ACTIVA',
            'observaciones' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function upsertAssignment(int $personalId, int $weaponId, string $obs): void
    {
        $now = now();
        $status = 'ASIGNADA';

        $existing = DB::table('weapon_assignments')
            ->where('personal_id', $personalId)
            ->where('weapon_id', $weaponId)
            ->first();

        if ($existing) {
            DB::table('weapon_assignments')
                ->where('id', $existing->id)
                ->update([
                    'status' => $status,
                    'observaciones' => $this->compactObs($obs),
                    'updated_at' => $now,
                ]);
            return;
        }

        DB::table('weapon_assignments')->insert([
            'personal_id' => $personalId,
            'weapon_id' => $weaponId,
            'fecha_asignacion' => Carbon::now()->toDateString(),
            'fecha_devolucion' => null,
            'status' => $status,
            'observaciones' => $this->compactObs($obs),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function compactObs(string $obs)
    {
        $obs = trim(preg_replace('/\s+/', ' ', $obs));
        return $obs !== '' ? mb_substr($obs, 0, 1000) : null;
    }

    private function extractMatriculas(string $obs): array
    {
        $txt = strtoupper($obs);

        $cortas = [];
        $largas = [];

        if (preg_match_all('/ARMA\s*CORTA\s*([A-Z0-9\-]+)/i', $txt, $m)) {
            foreach ($m[1] as $mat) $cortas[] = $mat;
        }

        if (preg_match_all('/ARMA\s*LARGA\s*([A-Z0-9\-]+)/i', $txt, $m)) {
            foreach ($m[1] as $mat) $largas[] = $mat;
        }

        if (preg_match_all('/ARMAS?\s*([A-Z0-9\-]+)\s*\/\s*([A-Z0-9\-]+)/i', $txt, $m)) {
            for ($i = 0; $i < count($m[1]); $i++) {
                $a = $m[1][$i] ?? null;
                $b = $m[2][$i] ?? null;

                if ($a && !in_array($a, $cortas) && !in_array($a, $largas)) $cortas[] = $a;
                if ($b && !in_array($b, $cortas) && !in_array($b, $largas)) $largas[] = $b;
            }
        }

        $cortas = array_values(array_unique(array_filter($cortas)));
        $largas = array_values(array_unique(array_filter($largas)));

        return [$cortas, $largas];
    }
}
