<?php

namespace App\Http\Controllers;

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
        $actor = Auth::user();

        $users = User::query()
            ->with('roles')
            ->get();

        return view('admin.settings.users.index', compact('users'));
    }

    public function create()
    {
        $actor = Auth::user();

        $roles = Role::query()
            ->when(!$actor->hasRole('Superadmin'), function ($q) {
                $q->where('name', '!=', 'Superadmin');
            })
            ->get();

        return view('admin.settings.users.create', compact('roles'));
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
            abort(403, 'No autorizado.');
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

            Log::info("Usuario creado: {$user->id} {$user->name}");

            return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al crear usuario: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al crear el usuario.')->withInput();
        }
    }

    public function show($id)
    {
        $actor = Auth::user();

        $user = User::query()
            ->with('roles')
            ->findOrFail($id);

        return view('admin.settings.users.show', compact('user'));
    }

    public function edit($id)
    {
        $actor = Auth::user();

        $user = User::query()
            ->with('roles')
            ->findOrFail($id);

        $roles = Role::query()
            ->when(!$actor->hasRole('Superadmin'), function ($q) {
                $q->where('name', '!=', 'Superadmin');
            })
            ->get();

        return view('admin.settings.users.edit', compact('user', 'roles'));
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
            abort(403, 'No autorizado.');
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

            Log::info("Usuario actualizado: {$user->id} {$user->name}");

            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error al actualizar usuario: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al actualizar el usuario.')->withInput();
        }
    }

    public function destroy($id)
    {
        $actor = Auth::user();

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

            return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Error al eliminar usuario: " . $e->getMessage());
            return redirect()->back()->withErrors('Hubo un error al eliminar el usuario.');
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.settings.users.profile', compact('user'));
    }

    public function showChangePasswordForm()
    {
        return view('admin.settings.users.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no coincide.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', '¡Contraseña actualizada correctamente!');
    }
}
