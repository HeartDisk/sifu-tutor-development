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
                            Mobile App FAQ List
                         </h2>
                         <nav>
                            <ol class="breadcrumb breadcrumb-arrow mb-0">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                               <li class="breadcrumb-item active" aria-current="page">Mobile App FAQ List</li>
                            </ol>
                         </nav>
                      </div>
                      <div class="nk-block-head-content">
                         <ul class="d-flex">
                            <li><a href="{{route('faq.create')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                            @can("mobile-news-add")
                            <li><a href="{{route('faq.create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Mobile App FAQ</span></a></li>
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
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $numbers = 1;
                                    @endphp
                                    @foreach($faqs as $faq)
                                    <tr>
                                        <td>{{ $numbers++ }}</td>
                                        <td>{{ $faq->type }}</td>
                                        <td>{{ $faq->category }}</td>
                                        <td>{!! $faq->question !!}</td>
                                        <td>{!! $faq->answer !!}</td>
                                        <td>
                                            <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{ url('faq/'.$faq->id.'/edit') }}"><i class="fa fa-pencil"></i> </a>
                                            <a class="dtable-cbtn bt-view dtb-tooltip" dtb-tooltip="View" href="{{ url('faq/'.$faq->id) }}"><i class="fa fa-eye"></i> </a>
                                            <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this FAQ?');" href="{{ url('faq/delete', $faq->id) }}"><i class="fa fa-trash"></i> </a>
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