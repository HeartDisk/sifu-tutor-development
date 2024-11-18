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
                        User's Roles
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Analytics</a></li>
                           <li class="breadcrumb-item active" aria-current="page">User's Roles</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        @can("user-role-add")
                        <li><a href="#" class="btn btn-md d-md-none btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{url("/userRoles/addRole")}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Roles</span></a></li>
                        @endcan
                     </ul>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
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
                           <th>#</th>
                           <th>Role Name</th>
                           <th>created at</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $number = 1;
                        @endphp
                        @foreach($roles as $row)
                        <tr>
                           <td>{{$number++}}</td>
                           <td>{{$row->name}}</td>
                           <td>{{$row->created_at}}</td>
                           <td>
                              @can("user-edit-edit")
                              <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Role" data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><i class="fa fa-edit"></i> </a>
                              @endcan
                              @can("user-edit-delete")
                              <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Role" onclick="return confirm('Are you sure you want to delete this Role?');" href="{{route('showRole',['role' => $row->id])}}"><i class="danger fa fa-trash"></i> </a>
                              @endcan
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@foreach($roles as $rowTwo)
<div class="modal fade dtable-modal" id="editUserModal{{$rowTwo->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Edit Role</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="POST" action="{{route('editRole')}}">
               @csrf
               <input type="hidden" name="rolesID" value="{{$rowTwo->id}}"/>
               <div class="row g-3 mb-4">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <label for="firstname" class="form-label">Role Name</label>
                        <div class="form-control-wrap"><input type="text" class="form-control" name="roleName" value="{{$rowTwo->name}}"></div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button class="btn btn-success" type="submit">Edit Role</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endforeach
@foreach($roles as $rowThree)
<div class="modal fade dtable-modal" id="viewRolesModal{{$rowThree->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Edit Role</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="POST" action="{{route('editRole')}}">
               @csrf
               <input type="hidden" name="rolesID" value="{{$rowTwo->id}}"/>
               <div class="row g-3 mb-4">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <label for="firstname" class="form-label">Role Name</label>
                        <div class="form-control-wrap"><input type="text" class="form-control" name="roleName" value="{{$rowTwo->name}}"></div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button class="btn btn-success" type="submit">Edit Role</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endforeach
<div class="modal fade dtable-modal" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add Role</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="POST" action="{{route('addRole')}}">
               @csrf
               <div class="row g-3 mb-4">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <label for="firstname" class="form-label">Role Name</label>
                        <div class="form-control-wrap"><input type="text" class="form-control" name="roleName" placeholder="Role Name"></div>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="d-flex gap g-2">
                        <div class="gap-col"><button class="btn btn-primary" type="submit">Add Role</button></div>
                        <div class="gap-col"><button type="button" class="btn border-0" data-bs-dismiss="modal">Discard</button></div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection