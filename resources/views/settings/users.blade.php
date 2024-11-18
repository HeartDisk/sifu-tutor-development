@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Users List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                           <li class="breadcrumb-item active" aria-current="page"> Users List</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        @can("user-add")
                        <li><a href="#" class="btn btn-md d-md-none btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#addUserModal"><em class="icon ni ni-plus"></em><span>Add User</span></a></li>
                        @endcan
                     </ul>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     @if (\Session::has('success'))
                     <div class="alert alert-success">
                        <ul>
                           <li>{!! \Session::get('success') !!}</li>
                        </ul>
                     </div>
                     @endif
                     @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>Display Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Roles</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($users as $rowUsers)
                           @if($rowUsers->is_deleted == 0)
                           <tr>
                              <td>{{$rowUsers->name}}</td>
                              <td>{{$rowUsers->email}}</td>
                              <td>{{$rowUsers->phone}}</td>
                              <td>
                                 @php
                                 $roleName = DB::table('roles')->where('id','=',$rowUsers->role)->first();
                                 if($roleName){
                                 echo  $roleName->name;
                                 }
                                 @endphp
                              </td>
                              <td>
                                 @can("user-edit")
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Parent" data-bs-toggle="modal" data-bs-target="#editUser{{$rowUsers->id}}"><i class="fa fa-edit"></i> </a>
                                 @endcan
                                 @can("user-delete")
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Parent" onclick="return confirm('Are you sure you want to delete this User?');" href="{{route('deleteUser',$rowUsers->id)}}"><i class="danger fa fa-trash"></i> </a>
                                 @endcan
                              </td>
                           </tr>
                           @endif
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
  <div class="modal fade dtable-modal" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
           <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
           </div>
           <div class="modal-body">
              <form method="POST" action="{{route('submitUser')}}">
                 @csrf
                 <div class="row g-3 mb-4">
                    <div class="col-sm-12">
                       <h3>Personal Information</h3>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="firstname" class="form-label">Full Name</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="fullName" placeholder="Full Name"></div>
                       </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="lastname" class="form-label">Phone Number</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="phone" placeholder="Phone Number"></div>
                       </div>
                    </div>
                    <div class="col-sm-12">  
                      <h3>Login Information</h3>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="email" class="form-label">Email Address</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="email" placeholder="Email address"></div>
                       </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="email" class="form-label">Password</label>
                          <div class="form-control-wrap"><input type="password" name="password" class="form-control" placeholder="Password"></div>
                       </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="status" class="form-label">Status</label>
                          <div class="form-control-wrap">
                             <select class="js-select" data-search="true" name="status" data-sort="false">
                                <option value="">Select a status</option>
                                <option value="pending">Pending</option>
                                <option selected value="active">Active</option>
                                <option value="inactive">Inactive</option>
                             </select>
                          </div>
                       </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="role" class="form-label">Role</label>
                          <div class="form-control-wrap">
                             <select class="js-select" data-search="true" name="role" data-sort="false">
                                <option value="">Select a role</option>
                                @foreach($roles as $rowRoles)
                                <option value="{{$rowRoles->id}}">{{$rowRoles->name}}</option>
                                @endforeach
                             </select>
                          </div>
                       </div>
                    </div>
                    <div class="col-lg-12">
                       <div class="form-group">
                          <label for="email" class="form-label">Remark</label>
                          <div class="form-control-wrap">
                             <textarea class="form-control" data-val="true" data-val-length="The field Remark must be a string with a maximum length of 300." data-val-length-max="300" id="Remark" maxlength="300" name="remarks" row="2"></textarea>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Add User</button>
                 </div>
              </form>
           </div>
        </div>
     </div>
  </div>
  @foreach($users as $editRowUsers)
  <div class="modal fade dtable-modal" id="editUser{{$editRowUsers->id}}" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
           <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Edit User</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
           </div>
           <div class="modal-body">
              <form method="POST" action="{{route('editSubmitUser')}}">
                 @csrf
                 <input type="hidden" name="userID" value="{{$editRowUsers->id}}"/>
                 <div class="row g-3 mb-4">
                    <div class="col-sm-12">
                      <h3>Personal Information</h3>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="firstname" class="form-label">Full Name</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="fullName" value="{{$editRowUsers->name}}"></div>
                       </div>
                    </div>
                    <div class="col-lg-6">
                       <div class="form-group">
                          <label for="lastname" class="form-label">Phone Number</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="phone" value="{{$editRowUsers->phone}}"></div>
                       </div>
                    </div>
                    <div class="col-sm-12">
                      <h3>Login Information</h3>
                    </div>
                    <div class="col-lg-4">
                       <div class="form-group">
                          <label for="email" class="form-label">Email Address</label>
                          <div class="form-control-wrap"><input type="text" class="form-control" name="email" value="{{$editRowUsers->email}}"></div>
                       </div>
                    </div>
                    <div class="col-lg-4">
                       <div class="form-group">
                          <label for="status" class="form-label">Status</label>
                          <div class="form-control-wrap">
                             <select class="js-select" data-search="true" name="status" data-sort="false">
                                <option value="">Select a status</option>
                                <option {{$editRowUsers->status=="pending"?"selected":""}} value="pending">Pending</option>
                                <option {{$editRowUsers->status=="active"?"selected":""}} value="active">Active</option>
                                <option {{$editRowUsers->status=="inactive"?"selected":""}} value="inactive">Inactive</option>
                             </select>
                          </div>
                       </div>
                    </div>
                    <div class="col-lg-4">
                       <div class="form-group">
                          <label for="role" class="form-label">Role</label>
                          <div class="form-control-wrap">
                             @php
                             if($editRowUsers->role != NULL){
                             $role = DB::table('roles')->where('id', '=', $editRowUsers->role)->first();
                             }
                             @endphp
                             <select class="js-select" data-search="true" name="role" data-sort="false">
                                <option value="">Select a role</option>
                                <option selected value="{{$role->id}}">{{$role->name}}</option>
                                @foreach($roles as $rowRoles)
                                <option value="{{$rowRoles->id}}">{{$rowRoles->name}}</option>
                                @endforeach
                             </select>
                          </div>
                       </div>
                    </div>
                    <div class="col-lg-12">
                       <div class="form-group">
                          <label for="email" class="form-label">Remark</label>
                          <div class="form-control-wrap">
                             <textarea class="form-control" data-val="true" data-val-length="The field Remark must be a string with a maximum length of 300." data-val-length-max="300" id="Remark" maxlength="300" name="remarks" row="2">{{$editRowUsers->remarks}}</textarea>
                          </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                       <button class="btn btn-success" type="submit">Edit User</button>
                    </div>
                 </div>
              </form>
           </div>
        </div>
     </div>
  </div>
</div>
@endforeach
@endsection