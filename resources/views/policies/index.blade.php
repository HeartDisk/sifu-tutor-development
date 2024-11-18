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
                            Policies
                         </h2>
                         <nav>
                            <ol class="breadcrumb breadcrumb-arrow mb-0">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item"><a href="#">Policies</a></li>
                               <li class="breadcrumb-item active" aria-current="page">Policies List</li>
                            </ol>
                         </nav>
                      </div>
                      <div class="nk-block-head-content">
                         <ul class="d-flex">
                             <li><a href="{{route('policies.create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Policy</span></a></li>
                            <li><a href="{{route('policies.create')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                         </ul>
                      </div>
                   </div>
                </div>
                <div class="nk-block">
                    <div class="card">
                        <div class="card-body">
                            <table class="datatable-init table" data-nk-container="table-responsive">
                                <thead>
                                    <tr>
                                        <th>User Role</th>
                                        <th>Policy Type</th>
                                        <th>Content</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policies as $policy)
                                    <tr>
                                        <td>{{ ucfirst($policy->user_role) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $policy->policy_type)) }}</td>
                                        <td>{!! \Illuminate\Support\Str::limit($policy->content, 50) !!}</td>
                                        <td>
                                            <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{ url('policies/'.$policy->id.'/edit') }}"><i class="fa fa-pencil"></i> </a>
                                            <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{ url('policies/'.$policy->id) }}"><i class="fa fa-eye"></i> </a>
                                            <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this FAQ?');" href="{{ url('policies/delete', $policy->id) }}"><i class="fa fa-trash"></i> </a>
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
</div>
@endsection