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
                        Banner Ads List
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Banner Ads List</li>
                        </ol>
                     </nav>
                  </div>
                  <div class="nk-block-head-content">
                     <ul class="d-flex">
                        <li><a href="{{route('addBannerAds')}}" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a></li>
                        <li><a href="{{route('addBannerAds')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Banner Ads</span></a></li>
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
                              <th>Title</th>
                              <th>Display ON</th>
                              <th>Call To Action</th>
                              <th>Action Type</th>
                              <th>Banner</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $numbers = 1;
                           @endphp
                           @foreach($bannerAds as $rows)
                           <tr>
                              <td>{{$numbers++}}</td>
                              <td>{{$rows->displayOnPage}}</td>
                              <td>{{$rows->tutorStatusCriteria}}</td>
                              <td>{{$rows->callToActionType}}</td>
                              <td>{{$rows->pageToOpen}}</td>
                              <td><a class="dtable-status-viewfile" data-lightbox="image" target="_blank" href="{{$rows->urlToOpen}}">View Banner</a></td>
                              <td>
                                 {{-- @can("mobile-banner-advertise-details")--}}
                                 {{-- <i title="Detail"--}}
                                 {{-- style="border:1px solid #000; border-radius:5px; padding:5px;"--}}
                                 {{-- class="fa fa-eye"></i>--}}
                                 {{-- @endcan--}}
                                 {{-- @can("mobile-banner-advertise-edit")--}}
                                 {{-- <i title="Edit"--}}
                                 {{-- style="border:1px solid #000; border-radius:5px; padding:5px;"--}}
                                 {{-- class="fa fa-edit"></i>--}}
                                 {{-- @endcan--}}
                                 @can("mobile-banner-advertise-delete")
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this Banner?');" href="{{route('deleteBannerAds',$rows->id)}}"><i class="fa fa-trash"></i> </a>
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
</div>
@endsection