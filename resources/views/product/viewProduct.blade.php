@extends('layouts.main')

@section('content')

        <div class="nk-content">
            <div class="fluid-container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="nk-block-head">
                    <div class="nk-block-head">
                      <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                        <div class="nk-block-head-content">
                          <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <div class="mt-3 mt-md-0 ms-md-3">
                              <h1 class="title mb-1">Subject Detail</h1>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  <div class="nk-block">
                    <div class="card card-gutter-md">
                      <div class="card-body">
                        
                        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <h2>{{$product->name}}</h2>
                    </div>
                    <div class="col-sm-12 col-md-4">
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-sm bg-gradient" role="progressbar" aria-valuenow="41.66666666666667" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height:5px;"></div>
                </div>
            </div>

            <div class="card-body">
                <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                    <div class="col-md-3 details-item">
                        <p class="item-title">Subject Name</p>
                        <p><strong>{{$product->name}}</strong></p>
                    </div>

                    <div class="col-md-3 details-item">
                        <p class="item-title">Product Image</p>
                        @if($product->image)
                            <img src="{{ asset('public/images/products/' . $product->image) }}" alt="Product Image" width="50" height="50">
                        @else
                            No Image
                        @endif
                    </div>
                    
                    <div class="col-md-3 details-item">
                        <p class="item-title">Brand</p>
                        <p><strong>SifuTutor</strong></p>
                    </div>
                </div>
                <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                    <div class="col-md-3 details-item">
                        <p class="item-title">Levels</p>
                        <p><strong>{{$product->category}}</strong></p>
                    </div>
                    
                    <div class="col-md-3 details-item">
                        <p class="item-title">Fees Per Hour</p>
                        <p><strong>RM {{$product->price}}</strong></p>
                    </div>
                </div>
                    <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                        <div class="col-md-3 details-item">
                            <p class="item-title">Commision Rate (Before Training)</p>
                            <p><strong>RM {{$product->CommissionRateBeforeTraining}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Incentive Rate (Before Training)</p>
                            <p><strong>RM {{$product->IncentiveRateBeforeTraining}}</strong></p>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                        <div class="col-md-3 details-item">
                            <p class="item-title">Commision Rate (After Training)</p>
                            <p><strong>RM {{$product->CommissionRateAfterTraining}}</strong></p>
                        </div>
                        <div class="col-md-3 details-item">
                            <p class="item-title">Incentive Rate (After Training)</p>
                            <p><strong>RM {{$product->IncentiveRateAfterTraining}}</strong></p>
                        </div>
                    </div>
                <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                    <div class="col-md-3 details-item">
                        <p class="item-title">Description</p>
                        <p><strong>{{$product->description}}</strong></p>
                    </div>
                    <div class="col-md-3 details-item">
                        <p class="item-title">Remark</p>
                        <p><strong>{{$product->remarks}}</strong></p>
                    </div>
                </div>
                <div style="border-bottom: 1px solid #000 !important;" class="row row-details">
                    <div class="col-md-3 details-item">
                        <p class="item-title">LastUpdateDateTime</p>
                        <p><strong>{{$product->updated_at}}</strong></p>
                    </div>

                    <div class="col-md-3 details-item">
                        <p class="item-title">CreationDateTime</p>
                        <p><strong>{{$product->created_at}}</strong></p>
                    </div>
                </div>
                <div class="row row-details pb-5">
                    <div class="col-md-3">
                        <a class="btn btn-light waves-effect waves-light" href="{{route('subjectList')}}">Back</a>
                    </div>
                </div>
            </div>
        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
         

@endsection
