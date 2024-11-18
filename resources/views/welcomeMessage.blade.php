@extends('layouts.login')

@section('content')
<div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap align-items-center justify-content-center has-mask">
          <div class="mask mask-3"></div>
          <div class="container p-2 p-sm-4">
            <div class="row flex-lg-row-reverse">
              <div class="col-lg-5">
                <div class="card card-gutter-lg rounded-4 card-auth">
                  <div class="card-body">
                    <div class="brand-logo mb-4">
                      <a href="{{url('/')}}" class="logo-link">
                        <div class="logo-wrap">
                        <img style="width:200px; height:80px;" src="{{asset('template/login.png')}}" alt="Sifututor" class="icon">
                        </div>
                      </a>
                    </div>
                    <div class="nk-block-head">
                      <h1>Dear Parents / Guardians, <br/> Thank you for Feedback </h1>
                    </div>
                   <div>
                       <p> </p>
                   </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-7 align-self-center">
                <div class="card-body is-theme ps-lg-4 pt-5 pt-lg-0">
                  <div class="row">
                    <div class="col-sm-8">
                      <div class="h1 title mb-3">Welcome back to <br> our community. </div>
                      <p>YOUR NO 1 CHOICE OF HOME TUITION </p>
                    </div>
                  </div>
                  <div class="mt-5 pt-4">
                    <div class="media-group media-group-overlap">
                    <div class="media media-sm media-circle media-border border-white">
                        <img src="{{asset('template/images/avatar/a.jpg')}}" alt="">
                      </div>
                      <div class="media media-sm media-circle media-border border-white">
                        <img src="{{asset('template/images/avatar/b.jpg')}}" alt="">
                      </div>
                      <div class="media media-sm media-circle media-border border-white">
                        <img src="{{asset('template/images/avatar/c.jpg')}}" alt="">
                      </div>
                      <div class="media media-sm media-circle media-border border-white">
                        <img src="{{asset('template/images/avatar/d.jpg')}}" alt="">
                      </div>
                    </div>
                    <p class="small mt-2">More than 2k people joined us, it's your turn</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
