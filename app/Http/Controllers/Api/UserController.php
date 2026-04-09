<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'area' => $user->area,
                    'estado' => $user->estado,
                    'roles' => $user->roles->pluck('name')->values(),
                    'created_at' => optional($user->created_at)->toDateTimeString(),
                    'updated_at' => optional($user->updated_at)->toDateTimeString(),
                ];
            });

        return response()->json([
            'ok' => true,
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::query()
            ->with('roles')
            ->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'area' => $user->area,
                'estado' => $user->estado,
                'roles' => $user->roles->pluck('name')->values(),
                'created_at' => optional($user->created_at)->toDateTimeString(),
                'updated_at' => optional($user->updated_at)->toDateTimeString(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $actor = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'area' => 'nullable|string|max:120',
            'role' => 'required|exists:roles,name',
        ]);

        if (!$actor->hasRole('Superadmin') && $validatedData['role'] === 'Superadmin') {
            return response()->json([
                'ok' => false,
                'message' => 'No autorizado.',
            ], 403);
        }

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'estado' => 'Activo',
                'area' => $validatedData['area'] ?? null,
            ]);

            $user->assignRole($validatedData['role']);
            $user->load('roles');

            Log::info("Usuario creado: {$user->id} {$user->name}");

            return response()->json([
                'ok' => true,
                'message' => 'Usuario creado correctamente.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'area' => $user->area,
                    'estado' => $user->estado,
                    'roles' => $user->roles->pluck('name')->values(),
                    'created_at' => optional($user->created_at)->toDateTimeString(),
                    'updated_at' => optional($user->updated_at)->toDateTimeString(),
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error al crear usuario: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al crear el usuario.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $actor = Auth::user();

        $user = User::query()
            ->with('roles')
            ->findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'area' => 'nullable|string|max:120',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!$actor->hasRole('Superadmin') && $validatedData['role'] === 'Superadmin') {
            return response()->json([
                'ok' => false,
                'message' => 'No autorizado.',
            ], 403);
        }

        if ($user->hasRole('Superadmin') && $validatedData['role'] !== 'Superadmin') {
            $superadmins = User::role('Superadmin')->count();

            if ($superadmins <= 1) {
                throw ValidationException::withMessages([
                    'role' => 'No puedes dejar el sistema sin Superadmin.',
                ]);
            }
        }

        try {
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'area' => $validatedData['area'] ?? null,
            ]);

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
                $user->save();
            }

            $user->syncRoles([$validatedData['role']]);
            $user->load('roles');

            Log::info("Usuario actualizado: {$user->id} {$user->name}");

            return response()->json([
                'ok' => true,
                'message' => 'Usuario actualizado correctamente.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'area' => $user->area,
                    'estado' => $user->estado,
                    'roles' => $user->roles->pluck('name')->values(),
                    'created_at' => optional($user->created_at)->toDateTimeString(),
                    'updated_at' => optional($user->updated_at)->toDateTimeString(),
                ],
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error al actualizar usuario: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al actualizar el usuario.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::query()->findOrFail($id);

            if ($user->hasRole('Superadmin')) {
                $superadmins = User::role('Superadmin')->count();

                if ($superadmins <= 1) {
                    throw ValidationException::withMessages([
                        'user' => 'No puedes eliminar al último Superadmin.',
                    ]);
                }
            }

            $user->delete();

            Log::info("Usuario eliminado: {$user->id} {$user->name}");

            return response()->json([
                'ok' => true,
                'message' => 'Usuario eliminado correctamente.',
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error al eliminar usuario: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => 'Hubo un error al eliminar el usuario.',
            ], 500);
        }
    }

    public function catalogos()
    {
        $actor = Auth::user();

        $roles = Role::query()
            ->when(!$actor->hasRole('Superadmin'), function ($q) {
                $q->where('name', '!=', 'Superadmin');
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'ok' => true,
            'data' => [
                'roles' => $roles,
            ],
        ]);
    }
}
