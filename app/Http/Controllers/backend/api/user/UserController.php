<?php

namespace App\Http\Controllers\backend\api\user;

use App\Models\User;

use App\Models\UserInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    //
    private $user;
    public function __construct(){
        $this->user =new User();
    }

    public function imageUploadProfile(Request $request)
    {
        $name = singleImgUpload($request,"img/users");
        $user = User::where('id',$request->id)->update([
            'thumbnail' => $name,
        ]);
        return response()->json([
            'thumbnail' => $name
        ]);

    }
    public function index(Request $request){

        $users = User::latest();
        $users = $this->filterRecords($request, $users);

        $users =  $users->with(['roles'])->paginate(env('PAR_PAGE'));

         $roles=Role::orderBy('name', 'asc')->get();

     return response()->json(['users' => $users,'roles'=>$roles], 200);

    }
    public function filterRecords($request, $users)
    {

      
        if ($request->get('query') != '') {
            $users = $users->where('name', 'LIKE', '%' . $request->get('query') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('query') . '%');
        }
        if ($request->get('status') != '') {
            $users =   $users->where('status', $request->get('status'));
        }
        if ($request->get('role') != '') {
            $users =   $users->whereHas('roles',function($q) use ($request) {
                $q->where('id',$request->role);
            });
        }
        return $users;
    }

    public function edit($id){
        $user=User::where('uid',$id)->with('roles')->first();
        return response()->json(['user'=>$user]);
    }
    public function show($id){
        $user=User::where('uid',$id)->with('roles')->first();
        return response()->json(['user'=>$user]);
    }
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255','min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'city'=>['min:2'],
            // 'about'=>['required','min:5','max:255'],
            'username' => ['string',  'max:255', 'unique:users'],
            'phone' => ['string',  'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user_name=!empty($request->username) ? $request->username  : strtolower(trim($request->name)).rand(10,10000);

        $new_user =  $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'uid'=>Str::uuid(),
            'username'=> $user_name,
            'thumbnail'=>$request->thumbnail,
            'city' => $request->city,
            'country' => $request->country,
            'state' => $request->state,
            'address' => $request->address,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        if(!empty($request->roles['name'])){
            $new_user->assignRole($request->roles['name']);
        }
        else {
            $new_user->assignRole('customer');
        }


        event(new Registered($new_user));

        return response()->json(['user' => $new_user,'message' =>'User has been registration successfully'], 200);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,'.$id],
            'username' => ['max:255', 'unique:users,id,'.$id],
            'phone' => ['string',  'max:255', 'unique:users,id,'.$id],
        ]);
        if(!empty($request->password)){
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
        }
        $user= User::where('id',$id)->first();
        $update_user=User::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'thumbnail'=>$request->thumbnail,
            'city' => $request->city,
            'country' => $request->country,
            'state' => $request->state,
            'address' => $request->address,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'phone' => $request->phone,
        ]);


        return response()->json(['user' => $update_user,'message' =>'user has been updated successfully'], 200);
    }

    public function mapFirstLastName($request){
        return $request->first_name.$request->last_name;
    }
    public function destroy($id)
    {

        $user=User::destroy($id);
        if($user)
          return response()->json(['message' => 'user has been destroyed successfully'], 200);
        else
            return response()->json(['message' => 'user has not been not found'], 400);
    }
    public function updateAuthUser(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,'.$request->id],
        ]);
        $user = User::find($request->id);

        if(!empty($request->password)){
            $request->validate([
                'current_password' => ['required','current_password'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
            $user->save();
        }

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'meta_title'=>Str::slug($request->meta_title),
            'meta_description'=>Str::slug($request->meta_description),
            'phone'=> $request->phone,
            'bio'=> $request->bio,
        ]);
        // $roles_collection=Role::whereIn('id', [11,13])->get();
        // $new_user->permissions()->attach($permission_collection);


        return response()->json(['user' => $user,'message' =>'User has been updated successfully'], 200);
    }

}
