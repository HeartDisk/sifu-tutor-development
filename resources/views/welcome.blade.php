@extends('layouts.login')

@section('content')
<style>
#slideset3 {height: 3em; position: relative;
  overflow: hidden}
#slideset3 > * {height: 100%; box-sizing: border-box;
  overflow: hidden}
  #slideset3 > *:first-child {
  animation: 12s autoplay3 infinite ease-in-out; font-weight:700;}
@keyframes autoplay3 {
  0% {margin-top: 3em}
  4% {margin-top: 0em}
  31% {margin-top: 0em}
  35% {margin-top: -3em}
  64% {margin-top: -3em}
  68% {margin-top: -6em}
  96% {margin-top: -6em}
  100% {margin-top: -9em}
}
a.btn.btn-link {
    color: #0c1a6f;
}
input:focus{
    border-color: #0c1a6f;
    outline: 1px solid #0c1a6f;
}
</style>
<div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap align-items-center justify-content-center has-mask">
          <div class="mask mask-3"></div>
          <div class="container p-2 p-sm-4">
            <div class="row flex-lg-row-reverse">
              <div class="col-lg-4">
                <div class="card card-gutter-lg rounded-4 card-auth">
                  <div class="card-body">
                      
                      
                      
                      
                      
                      <div class="w-px-400 mx-auto">
      <!-- Logo -->
      
      <!-- /Logo -->
      <h3 class="mb-1 fw-bold">Welcome to Sifututor!</h3>
      <p style="font-size:14px;">Please sign-in to your account and start the adventure</p>

        <!-- Session Status -->
        
        <!-- Validation Errors -->
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
          <label for="email" class="form-label">Email or Username</label>
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  <div class="fv-plugins-message-container invalid-feedback"></div></div>
                  
                
        <div class="mb-3 form-password-toggle fv-plugins-icon-container">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Password</label>
            

          </div>
          <div class="input-group input-group-merge has-validation">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                      </div><div class="fv-plugins-message-container invalid-feedback"></div>
                      <a href="https://sifututor.odits.co/forgot-password" style="text-align-right">
                 @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}" style="text-align-right; width:100%;padding:0; justify-content: left;margin-top:10px;">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
             
            </a>
        </div>
        <div class="mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary d-grid w-100 waves-effect waves-light" style="background: #0c1a6f;">
                                    {{ __('Login') }}
                                </button>
      <input type="hidden"></form>

    </div>
                </div>
              </div>
              <div class="col-lg-7 align-self-center">
                <div class="card-body is-theme ps-lg-4 pt-5 pt-lg-0">
                  <div class="row">
                      <div class="app-brand">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          
<img style="width:500px;" src="{{asset('template/sifu-logo.gif')}}" alt="Sifututor" class="icon">
        </a>
      </div>
<!--      <div class="row" style="flex-wrap:nowrap;">-->
<!--          <h1 style="margin-right:-20px;width:auto !important;font-weight: 800 !important;font-size:32px" >-->
<!--    Admin, -->
<!--</h1>-->
<!--                    <div id="slideset3" style="width:auto !important">-->
<!--  <div style="width:auto !important">-->
<!--    <h1 style="width:auto !important;font-weight: 800 !important;font-size:32px;text-decoration:underline;">Selamat Hari </h1>-->
<!--  </div>-->
<!--  <div style="width:auto !important">-->
<!--    <h1 style="width:auto !important;font-weight: 800 !important;font-size:32px;text-decoration:underline">Zài Huì </h1>-->
<!--  </div>-->
<!--  <div style="width:auto !important">-->
<!--    <h1 style="width:auto !important;font-weight: 800 !important;font-size:32px;text-decoration:underline">Good Day </h1>-->
<!--  </div>-->
<!--</div>-->
    
</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection