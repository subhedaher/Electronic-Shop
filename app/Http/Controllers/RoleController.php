<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->paginate(5);
        return view('cms.roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guards = ['admin'];
        return view('cms.roles.create', ['guards' => $guards]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'guard' => 'required|string|in:admin,writer',
            'name' => 'required|string'
        ]);

        if (!$validator->fails()) {
            Role::create([
                'guard_name' => $request->input('guard'),
                'name' => $request->input('name')
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Role Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = Permission::where('guard_name', '=', $role->guard_name)->paginate(10);
        $rolePermissions = $role->permissions;

        foreach ($rolePermissions as $rolePermission) {
            foreach ($permissions as $permission) {
                if ($rolePermission->id == $permission->id) {
                    $permission->setAttribute("assigned", true);
                }
            }
        }
        return view('cms.roles.show', ['role' => $role, 'permissions' => $permissions]);
    }

    public function updateRolePermission(Request $request)
    {
        $validator = validator($request->all(), [
            'role_id' => 'required|numeric|exists:roles,id',
            'permission_id' => 'required|numeric|exists:permissions,id'
        ]);

        if (!$validator->fails()) {
            $role = Role::findOrFail($request->input('role_id'));
            $permission = Permission::findOrFail($request->input('permission_id'));
            $role->hasPermissionTo($permission) ? $role->revokePermissionTo($permission) : $role->givePermissionTo($permission);
            return response()->json([
                'status' => true,
                'message' => 'Update Roles'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $guards = ['admin'];
        return view('cms.roles.edit', ['role' => $role, 'guards' => $guards]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = validator($request->all(), [
            'guard' => 'required|string|in:admin',
            'name' => 'required|string'
        ]);

        if (!$validator->fails()) {
            $role->guard_name = $request->input('guard');
            $role->name = $request->input('name');
            $role->save();
            return response()->json([
                'status' => true,
                'message' => 'Role Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $countRows = Role::destroy($role->id);
        return response()->json([
            'status' => $countRows,
            'message' => $countRows ? "Role Deleted Successfully" : "Role Deleted Failed!"
        ], $countRows ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
