<?php

namespace App\Http\Controllers\backend\api\role;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
     //
     public function index(){
        $q=!empty(request('query')) ? request('query') : '';
        $roles = Role::latest()->where('name', 'LIKE', '%' . $q . '%')
                               ->with('permissions')
                               ->paginate(env('PAR_PAGE'));
        $users=User::select('id','name')->orderBy('name', 'ASC')->get();
        $permissions=Permission::select('id','name')->orderBy('name', 'ASC')->get();
     return response()->json(['roles'=>$roles,'users' => $users,'permissions'=>$permissions], 200);
    }
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:roles'],
        ]);

        $users_ids=collect($request->users)->pluck('id');

        $users=User::WhereIn('id',   $users_ids)->get();

        $role = Role::create(['name' =>Str::slug( $request->name,'-')]);
        $role->syncPermissions($request->permissions);
        return response()->json(['message'=>'Role has been created successfully.','role'=>$role]);
    }
    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255','unique:roles,name,'.$id],
        ]);
        $role = Role::find($id);

        $role->name=Str::slug($request->name,'-');
        $role->save();
        $role->syncPermissions($request->permissions);
        // $users_ids=collect($request->users)->pluck('id');
        // $users=User::WhereIn('id',   $users_ids)->get();
        // $role->users()->sync($users);
        return response()->json(['message'=>'Role has been updated successfully.','role'=>$role]);
    }
    public function destroy($id)
    {
        $role=Role::destroy($id);
        return response()->json(['message'=>'Role has been deleted successfully.']);
    }
    public function getRoles(Request $request){
        $roles=Role::orderBy('name','asc')->get();
        return response()->json(['roles'=>$roles]);
    }
}
