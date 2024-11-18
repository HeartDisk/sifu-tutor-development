@extends('layouts.main')

@section('content')

<div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                      <div class="nk-block-head-content">
                        <h2 class="nk-block-title">
                        User's Roles</h1>
                        <nav>
                          <ol class="breadcrumb breadcrumb-arrow mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">User Manage</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                        <thead class="table-dark">
                          <tr>
                             <th class="tb-col"><span class="overline-title">#</span></th>
                            <th class="tb-col"><span class="overline-title">Role Name</span></th>
                            <th class="tb-col"><span class="overline-title">created at</span></th>
                            <th class="tb-col tb-col-end" data-sortable="false"><span class="overline-title">Action</span></th>
                          </tr>
                        </thead>
                        <tbody>
                            @php
                                $number = 1;
                            @endphp
                            @foreach($roles as $row)
                            <tr>
                                <td>{{$number++}}</td>
                                <td class="tb-col">
                                  <div class="media-group">
                                    <div class="media-text">{{$row->name}}</div>
                                  </div>
                                </td>
                                <td class="tb-col">
                                  <div class="media-group">
                                    <div class="media-text">{{$row->created_at}}</div>
                                  </div>
                                </td>
                                <style>
                                    .btn>a{
                                        color:white;
                                    }
                                </style>
                                <td class="tb-col tb-col-end">

                                    <button class="btn btn-primary">
                                        <a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}">View</a>
                                    </button>
                                    @can("user-edit-edit")
                                    <button class="btn btn-warning">
                                        <a style="color:white" href="{{route('showRole',['role' => $row->id])}}">Edit</a>
                                    </button>
                                    @endcan

                                    @can("user-edit-delete")
                                    <button class="btn btn-danger">
                                        <a  style="color:white" href="{{route('showRole',['role' => $row->id])}}">Delete</a>
                                    </button>
                                    @endcan


{{--                                    <a href="{{route('deleteRole',['role' => $row->id])}}"><em class="icon ni ni-delete"></em><span>Edit</span></a>--}}
{{--                                    <a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><em class="icon ni ni-eye"></em><span>View Details</span></a>--}}
{{--                                  <div class="dropdown">--}}
{{--                                    <a href="#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>--}}
{{--                                      <a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><em class="icon ni ni-eye"></em><span>View Details</span></a>--}}
{{--                                      <a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><em class="icon ni ni-eye"></em><span>View Details</span></a>--}}
{{--                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">--}}
{{--                                      <div class="dropdown-content py-1">--}}
{{--                                        <ul class="link-list link-list-hover-bg-primary link-list-md">--}}
{{--                                            <li><a data-bs-toggle="modal" data-bs-target="#editUserModal{{$row->id}}" href=""><em class="icon ni ni-edit"></em><span>Edit</span></a></li>--}}
{{--                                            <li><a href="{{route('showRole',['role' => $row->id])}}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>--}}
{{--                                          <!--<li><a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>-->--}}
{{--                                          <li><a data-bs-toggle="modal" data-bs-target="#viewRolesModal{{$row->id}}"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>--}}
{{--                                        </ul>--}}
{{--                                      </div>--}}
{{--                                    </div>--}}
{{--                                  </div>--}}
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
                                        <div class="modal fade" id="editUserModal{{$rowTwo->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h4 class="modal-title" id="addUserModalLabel">Edit Role</h4>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <form method="POST" action="{{route('editRole')}}">
                                                      @csrf
                                                      <input type="hidden" name="rolesID" value="{{$rowTwo->id}}"/>
                                                    <div class="row g-3">
                                                      <div class="col-lg-12">
                                                        <div class="form-group">
                                                          <label for="firstname" class="form-label">Role Name</label>
                                                          <div class="form-control-wrap"><input type="text" class="form-control" name="roleName" value="{{$rowTwo->name}}"></div>
                                                        </div>
                                                      </div>
                                                      <div class="col-lg-12">
                                                        <div class="d-flex gap g-2">
                                                          <div class="gap-col"><button class="btn btn-primary" type="submit">Edit Role</button></div>
                                                          <div class="gap-col"><button type="button" class="btn border-0" data-bs-dismiss="modal">Discard</button></div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                            @endforeach


                            @foreach($roles as $rowThree)
                                        <div class="modal fade" id="viewRolesModal{{$rowThree->id}}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h4 class="modal-title" id="addUserModalLabel">Edit Role</h4>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <form method="POST" action="{{route('editRole')}}">
                                                      @csrf
                                                      <input type="hidden" name="rolesID" value="{{$rowTwo->id}}"/>
                                                    <div class="row g-3">
                                                      <div class="col-lg-12">
                                                        <div class="form-group">
                                                          <label for="firstname" class="form-label">Role Name</label>
                                                          <div class="form-control-wrap"><input type="text" class="form-control" name="roleName" value="{{$rowTwo->name}}"></div>
                                                        </div>
                                                      </div>
                                                      <div class="col-lg-12">
                                                        <div class="d-flex gap g-2">
                                                          <div class="gap-col"><button class="btn btn-primary" type="submit">Edit Role</button></div>
                                                          <div class="gap-col"><button type="button" class="btn border-0" data-bs-dismiss="modal">Discard</button></div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                            @endforeach


            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="addUserModalLabel">Add Role</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="POST" action="{{route('addRole')}}">
                          @csrf
                        <div class="row g-3">
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
