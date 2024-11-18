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
                                    Subject List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Subjects</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Subject List</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    <li><a href="{{route('addProduct')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                                    @can("tutor-add")
                                        <li><a href="{{route('addProduct')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Subject</span></a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('subjectList') }}" method="GET">

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <input type="text" name="searchQuery" class="form-control" placeholder="Search Subject, Category, Mode" value="{{ request('searchQuery') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{url()->current()}}" class="btn btn-danger">Reset</a>
                                        </div>
                                    </div>
                                </form>
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th> <!-- Add this column for the image -->
                                        <th>Subject Name</th>
                                        <th>Category</th>
                                        <th>Mode</th>
                                        <th>Fees Per Hour</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $numbers = 1;
                                    @endphp
                                    @foreach($products as $rows)
                                        <tr>
                                            <td>{{$numbers++}}</td>
                                            <td>
                                                @if($rows->image)
                                                    <img src="{{ asset('public/images/products/' . $rows->image) }}" alt="Product Image" width="50" height="50">
                                                @else
                                                    No Image
                                                @endif
                                            </td> <!-- Show the image here -->
                                            <td>{{$rows->name}}</td>
                                            <td>{{$rows->category_name}}</td>
                                            <td>
                                                @if($rows->mode === 'online')
                                                    Online
                                                @elseif($rows->mode === 'physical')
                                                    Physical
                                                @else
                                                    {{$rows->mode}}
                                                @endif
                                            </td>
                                            <td>{{$rows->category_price}}</td>
                                            <td>Active</td>
                                            <td>
                                                <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit Subject" href="{{route('editProduct',$rows->id)}}"><i class="fa fa-edit"></i></a>
                                                <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete Subject" onclick="return confirm('Are you sure you want to delete this Subject?');" href="{{route('deleteProduct',$rows->id)}}"><i class="danger fa fa-trash"></i></a>
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
