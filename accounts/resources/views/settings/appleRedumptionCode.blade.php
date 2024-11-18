@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title"></h1>
                                 <table style="width:100%">
                                    <tbody><tr>
                                       <td><h1 class="nk-block-title">APPLE REDEMPTION CODE</h1></td>
                                       <td><a href="{{route('addRedemptionCode')}}" style="float:right; " class="btn btn-primary nk-block-title"><i class="fa fa-user"></i> &nbsp;    Add Redemption Codes</a></td>
                                    </tr>
                                 </tbody></table>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Redemption Link</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" width="150">Action</th>
                                        </tr>
                                    </thead>
                                       <tbody>
                                       <tr>
                                            <th scope="row">1</th>
                                            <td>H4XA6EJFHJPT</td>
                                            <td>https://buy.itunes.apple.com/WebObjects/MZFinance.woa/wa/freeProductCodeWizard?code=H4XA6EJFHJPT</td>
                                            <td>Redeemed</td>
                                            <td>
                                                    <a href="/AppleRedemptionCodes/Details/1?returnUrl=%2FAppleRedemptionCodes%3Fpage%3D1%26status%3DAll%26sortOrder%3Dcreated" class="btn btn-outline-primary btn-table-action waves-effect waves-light"><span class="fa fa-eye"></span></a>
                                            </td>
                                        </tr>
                                          
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
