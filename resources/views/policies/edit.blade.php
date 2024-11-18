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
                            Edit Policy
                         </h2>
                         <nav>
                            <ol class="breadcrumb breadcrumb-arrow mb-0">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item"><a href="#">Policy List</a></li>
                               <li class="breadcrumb-item active" aria-current="page">Edit Policy</li>
                            </ol>
                         </nav>
                      </div>
                   </div>
                </div>
                <div class="nk-block">
                    <div class="card card-gutter-md">
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
                            <div class="bio-block">
                                <form action="{{ route('policies.update', $policy->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>User Role</label>
                                                <select name="user_role" class="form-control">
                                                    <option value="tutor" {{ $policy->user_role == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                                    <option value="parent" {{ $policy->user_role == 'parent' ? 'selected' : '' }}>Parent</option>
                                                </select>
                                    </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Policy Type</label>
                                                <select name="policy_type" class="form-control">
                                                    <option value="terms_of_service" {{ $policy->policy_type == 'terms_of_service' ? 'selected' : '' }}>Terms of Service</option>
                                                    <option value="privacy_statement" {{ $policy->policy_type == 'privacy_statement' ? 'selected' : '' }}>Privacy Statement</option>
                                                </select>
                                    </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Content</label>
                                                <textarea  id="editor2" name="content" class="form-control" rows="6">{{ $policy->content }}</textarea>
                                    </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection