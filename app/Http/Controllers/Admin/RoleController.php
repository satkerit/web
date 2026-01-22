<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('roles.view');

        $query = Role::withCount(['users', 'permissions']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('display_name', 'like', '%' . $request->search . '%');
            });
        }

        $roles = $query->orderBy('is_system', 'desc')->orderBy('display_name')->paginate(15)->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function show(Role $role)
    {
        $this->authorizeView('roles.view');

        $role->load(['permissions', 'users']);
        $permissionGroups = Permission::getGroups();

        return view('admin.roles.show', compact('role', 'permissionGroups'));
    }

    public function create()
    {
        $this->authorizeCreate('roles.create');

        $permissions = Permission::getGrouped();
        $permissionGroups = Permission::getGroups();

        return view('admin.roles.form', compact('permissions', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('roles.create');

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.regex' => 'Nama role hanya boleh menggunakan huruf kecil dan underscore.',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $this->authorizeEdit('roles.edit');

        $permissions = Permission::getGrouped();
        $permissionGroups = Permission::getGroups();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.form', compact('role', 'permissions', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeEdit('roles.edit');

        // Different validation rules for system roles
        $rules = [
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];

        // Only validate name for non-system roles
        if (!$role->is_system) {
            $rules['name'] = 'required|string|max:50|unique:roles,name,' . $role->id . '|regex:/^[a-z_]+$/';
        }

        $validated = $request->validate($rules, [
            'name.regex' => 'Nama role hanya boleh menggunakan huruf kecil dan underscore.',
        ]);

        $updateData = [
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only update name for non-system roles
        if (!$role->is_system && isset($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }

        $role->update($updateData);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $this->authorizeDelete('roles.delete');

        if ($role->is_system) {
            return redirect()->route('admin.roles.index')->with('error', 'Role sistem tidak dapat dihapus.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
