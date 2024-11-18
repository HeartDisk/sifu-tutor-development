<?php

namespace App\Http\Controllers\backend\api\role;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $q = !empty(request('query')) ? request('query') : '';
        $permissions = Permission::latest()->where('name', 'LIKE', '%' . $q . '%')
            ->with('roles:id,name', 'users:id,name')
            ->paginate(env('PAR_PAGE'));
        $users = User::select('id', 'name')->orderBy('name', 'ASC')->get();
        $roles = Role::select('id', 'name')->orderBy('name', 'ASC')->get();
        return response()->json(['permissions' => $permissions, 'roles' => $roles, 'users' => $users], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:permissions'],
            'roles' => ['required'],
        ]);

        $roles = Role::whereIn('id', collect($request->roles)->pluck('id'))->get();
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);

        $permission->roles()->sync($roles);




        return response()->json(['message' => 'Permission has been created successfully.', 'permission' => $permission]);
    }
    public function show($id)
    {
       $permission = Permission::with('roles:id,name', 'users:id,name')->findOrFail($id);
       return response()->json(['permission' => $permission]);
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $request->id],
        ]);

        $permission = Permission::find($request->id);
        $permission->name = $request->name;
        $permission->save();

        $roles = Role::WhereIn('id', $request->roles)->get();
        $users = User::WhereIn('id', $request->users)->get();

        $permission->users()->sync($users);
        $permission->roles()->sync($roles);

        return response()->json(['message' => 'Permission has been updated successfully.', 'permission' => $permission]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::destroy($id);
        return response()->json(['message' => 'Permission has been deleted successfully.']);
        //
    }

    public function permissionList()
    {
        $permissions = Permission::latest()->orderBy('name');
        return response()->json(['permissions' => $permissions]);
    }
}
