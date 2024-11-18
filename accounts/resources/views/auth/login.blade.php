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
                      <div class="nk-block-head-content">
                        <h3 class="nk-block-title mb-1">Login to Account</h3>
                        <p class="small">Please sign-in to your account and start the adventure.</p>
                      </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-lg-7 align-self-center">
                <div class="card-body is-theme ps-lg-4 pt-5 pt-lg-0">
                  <div class="row">
                    <div class="col-sm-8">
                      <div class="h1 title mb-3">Welcome back to <br> our community. </div>
                      <p>YOUR NO 1 CHOICE OF HOME TUITION <br/>Sign In To get Started.</p>
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
