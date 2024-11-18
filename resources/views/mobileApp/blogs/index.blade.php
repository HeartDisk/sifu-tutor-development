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
                                    Mobile App Blog List
                                </h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Mobile App Blog List</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="d-flex">
                                    <li><a href="{{route('addBlog')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                                    @can("mobile-news-add")
                                        <li><a href="{{route('addBlog')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Create Blog</span></a></li>
                                    @endcan
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
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Subject</th>
                                        <th>Pre Header</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $numbers = 1;
                                    @endphp
                                    @foreach($news as $rows)
                                        @if($rows->is_deleted == 0)
                                            <tr>
                                                <td>{{$numbers++}}</td>
                                                <td>
                                                    @if($rows->headerimage)
                                                        <a href="{{URL::asset('/public/MobileBlogImages/'.$rows->headerimage)}}" data-lightbox="image"><img src="{{URL::asset('/public/MobileBlogImages/'.$rows->headerimage)}}" width="30px" height="30px" alt="profile Pic"></a>
                                                    @endif
                                                </td>
                                                <td>{{$rows->subject}}</td>
                                                <td>{{$rows->preheader}}</td>
                                                <td>{!! \Illuminate\Support\Str::limit($rows->content, 60, '...') !!}</td>
                                                <td>{{$rows->status}}</td>
                                                <td>
                                                    @can("mobile-news-edit")
                                                        <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{route('editBlog',$rows->id)}}"><i class="fa fa-pencil"></i> </a>
                                                    @endcan
                                                    @can("mobile-news-detail")
                                                        <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{route('singleMobileAppBlog',$rows->id)}}"><i class="fa fa-eye"></i> </a>
                                                    @endcan
                                                    @can("mobile-news-delete")
                                                        <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this News?');" href="{{route('deleteBlog',$rows->id)}}"><i class="fa fa-trash"></i> </a>
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
    </div>
@endsection
