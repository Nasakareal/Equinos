<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Cache spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /**
         * Permisos reales según tus rutas web.php
         */
        $permissions = [
            // Configuraciones (settings)
            'ver configuraciones',

            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // Roles
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',

            // Personal
            'ver personal',
            'crear personal',
            'editar personal',
            'eliminar personal',

            // Armamento (weapons) + asignaciones usan el mismo middleware can:ver armamento
            'ver armamento',
            'crear armamento',
            'editar armamento',
            'eliminar armamento',

            // Incidencias (incluye tipos)
            'ver incidencias',
            'crear incidencias',
            'editar incidencias',
            'eliminar incidencias',

            // Turnos / Turnos-horarios / Servicio
            'ver turnos',
            'crear turnos',
            'editar turnos',
            'eliminar turnos',

            // Reportes diarios
            'ver reportes',
            'crear reportes',
        ];

        DB::transaction(function () use ($permissions) {

            // 1) Crear permisos si no existen
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }

            // 2) Roles y sus permisos
            $roles = [
                // Superadmin: TODO
                'Superadmin' => $permissions,

                // Administrador: TODO (si luego quieres restringirlo, lo ajustamos)
                'Administrador' => $permissions,

                // Subdirector: ve operación y reportes, pero NO configura usuarios/roles
                'Subdirector' => [
                    'ver personal',
                    'ver armamento',
                    'ver incidencias',
                    'ver turnos',
                    'ver reportes',
                    'crear reportes',
                ],

                // Empleado: opera módulos, sin eliminar ni configuraciones
                'Empleado' => [
                    'ver personal',
                    'crear personal',
                    'editar personal',

                    'ver armamento',
                    'crear armamento',
                    'editar armamento',

                    'ver incidencias',
                    'crear incidencias',
                    'editar incidencias',

                    'ver turnos',
                    'editar turnos',

                    'ver reportes',
                ],

                // Observador: solo lectura
                'Observador' => [
                    'ver personal',
                    'ver armamento',
                    'ver incidencias',
                    'ver turnos',
                    'ver reportes',
                ],
            ];

            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]);

                $permissionsToAssign = Permission::whereIn('name', $rolePermissions)
                    ->where('guard_name', 'web')
                    ->get();

                $role->syncPermissions($permissionsToAssign);
            }

            // ====== HARD RULE: no quedarse sin Superadmin ======
            $superadminRole = Role::where('name', 'Superadmin')->where('guard_name', 'web')->first();
            if ($superadminRole) {
                $count = User::role('Superadmin')->count();

                if ($count === 0) {
                    $firstUser = User::orderBy('id')->first();
                    if ($firstUser) {
                        $firstUser->assignRole('Superadmin');
                    }
                }
            }
        });

        // por si acaso
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
