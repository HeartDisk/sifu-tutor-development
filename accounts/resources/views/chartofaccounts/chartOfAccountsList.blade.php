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
                                       <td><h1 class="nk-block-title">Chart of Account List</h1></td>
                                       <td><a href="{{route('addChartOfAccounts')}}" style="float:right; " class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;   Add Chart of Accounts</a></td>
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
                                             <th><span class="overline-title">Account Code</span></th>
                                             <th><span class="overline-title">Account Name</span></th>
                                             <th><span class="overline-title">Type</span></th>
                                             <th><span class="overline-title">Description</span></th>
                                             <th><span class="overline-title">Action</span></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                           @php
                                            $numbers = 1;
                                           @endphp
                                           @foreach($chartofaccounts as $rows)
                                              <tr>
                                                 <td>{{$numbers++}}</td>
                                                 <td>{{$rows->code}}</td>
                                                 <td>{{$rows->name}}</td>
                                                 <td>{{$rows->type}}</td>
                                                 <td>{{$rows->slug}}</td>
                                                 <td>
                                                    <a href="{{route('viewChartOfAccounts',$rows->id)}}"><i title="Detail" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-eye"></i></a>
                                                    <a href="{{route('editChartOfAccounts',$rows->id)}}"><i title="Edit" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-edit"></i></a>
                                                    <!--<i title="Delete" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-trash"></i>-->
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
