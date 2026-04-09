<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->orderBy('name')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'users_count' => $role->users_count,
                    'permissions_count' => $role->permissions_count,
                    'created_at' => optional($role->created_at)->toDateTimeString(),
                    'updated_at' => optional($role->updated_at)->toDateTimeString(),
                ];
            });

        return response()->json([
            'ok' => true,
            'data' => $roles,
        ]);
    }

    public function show($id)
    {
        $role = Role::query()
            ->with(['permissions:id,name', 'users:id,name,email'])
            ->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ];
                })->values(),
                'users' => $role->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                })->values(),
                'created_at' => optional($role->created_at)->toDateTimeString(),
                'updated_at' => optional($role->updated_at)->toDateTimeString(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            $role = Role::create([
                'name' => $validatedData['name'],
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Rol creado exitosamente.',
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'created_at' => optional($role->created_at)->toDateTimeString(),
                    'updated_at' => optional($role->updated_at)->toDateTimeString(),
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error al crear rol: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al crear el rol.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $validatedData['name'],
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Rol actualizado exitosamente.',
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'created_at' => optional($role->created_at)->toDateTimeString(),
                    'updated_at' => optional($role->updated_at)->toDateTimeString(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Error al actualizar rol: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al actualizar el rol.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::query()
                ->withCount('users')
                ->findOrFail($id);

            if ($role->users_count > 0) {
                throw ValidationException::withMessages([
                    'role' => 'El rol no puede ser eliminado porque tiene usuarios asociados.',
                ]);
            }

            $role->delete();

            return response()->json([
                'ok' => true,
                'message' => 'Rol eliminado exitosamente.',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error al eliminar rol: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al eliminar el rol.',
            ], 500);
        }
    }

    public function permissions($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::query()
            ->orderBy('name')
            ->get();

        $rolePermissions = $role->permissions->pluck('id')->map(fn ($id) => (int) $id)->values();

        $groupedPermissions = $permissions
            ->groupBy(function ($permission) {
                $name = trim($permission->name);
                $parts = preg_split('/\s+/', $name, 2);

                if (count($parts) < 2) {
                    return 'Otros';
                }

                return ucfirst($parts[1]);
            })
            ->map(function ($items, $group) {
                return [
                    'group' => $group,
                    'permissions' => $items->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'ok' => true,
            'data' => [
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                ],
                'role_permissions' => $rolePermissions,
                'grouped_permissions' => $groupedPermissions,
            ],
        ]);
    }

    public function assignPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validatedData = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $permissions = $validatedData['permissions'] ?? [];

            $role->syncPermissions($permissions);
            $role->load('permissions');

            return response()->json([
                'ok' => true,
                'message' => 'Permisos asignados correctamente.',
                'data' => [
                    'role' => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                    ],
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    })->values(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Error al asignar permisos al rol: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al asignar los permisos.',
            ], 500);
        }
    }
}
