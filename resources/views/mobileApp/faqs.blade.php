@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <table style="width:100%">
                                    <tr>
                                       <td><h1 class="nk-block-title">Mobile App FAQ List</h1></td>
                                       <td><a href="{{route('addFAQ')}}" style="float:right; " class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;   Add Mobile App FAQ</a></td>
                                    </tr>
                                 </table>

                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">Question</span></th>
                                             <th><span class="overline-title">Answer</span></th>
                                             <th><span class="overline-title">Action</span></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                           @php
                                            $numbers = 1;
                                           @endphp
                                           @foreach($news as $rows)
                                          
                                            <tr>
                                                 <td>{{$numbers++}}</td>
                                                
                                                 <td>{!! $rows->question !!}</td>
                                                 <td>{!! $rows->answer !!}</td>
                                                
                                                 <td style="width:200px;">
                                                 <!--<a href="{{route('editNews',$rows->id)}}">-->
                                                 <!--  <i title="Edit" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-edit"></i>-->
                                                 <!--</a>-->
                                                 <!--<a href="{{route('singleMobileAppNews',$rows->id)}}">-->
                                                 <!--  <i title="View" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-eye"></i>-->
                                                 <!--</a>-->
                                                 <a onclick="return confirm('Are you sure you want to delete this News?');" href="{{route('deleteNews',$rows->id)}}">
                                                    <i title="Delete News" style="color:red !important; border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-trash"></i>
                                                </a>    
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
