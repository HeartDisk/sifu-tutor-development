@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <table style="width:100%">
                                    <tr>
                                       <td><h1 class="nk-block-title">My Profile</h1></td>
                                    </tr>
                                 </table>

                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                     <form action="{{route('submitEditProfile')}}" method="POST" enctype="multipart/form-data">
                                         @csrf
                                         <input type="hidden" name="userID" value="{{$user->id}}" class="form-control"/>
                                    <h1>Personal Information</h1>
                                    <div class="row">
                                        <div class="col-md-4">
                                            
                                            @if($user->thumbnail)
                                                <img style="width:120px;" class="img-responsive mb-4" src="{{asset('public/userProfileImage/'.$user->userImage)}}"/>
                                            @else
                                                <img style="width:120px;" class="img-responsive mb-4" src="{{asset('public/profileImage.png')}}"/>
                                            @endif
                                            
                                            
                                            <input type="file" name="profileImage" class="form-control"/>
                                        </div>
                                        <div class="col-md-8">
                                        
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4 mb-4">
                                        <div class="col-md-3">
                                            <label>Fullname</label>
                                            <input type="text" name="fullName" value="{{$user->name}}" class="form-control"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone" value="{{$user->phone}}" class="form-control"/>
                                        </div>
                                    </div>
                                    
                                    <h1>Log-In Information</h1>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Email</label>
                                            <input readonly type="text" name="email"  value="{{$user->email}}"  class="form-control"/>
                                        </div>
                                        <div class="col-md-8">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <hr/>
                                            <label> <br/> </label>
                                            <input type="submit" class="btn btn-success" value="submit"/>
                                        </div>
                                        <div class="col-md-8">
                                        
                                        </div>
                                    </div>
                                    
                                    </form>
                                 </div>
                              </div>
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>


@endsection
