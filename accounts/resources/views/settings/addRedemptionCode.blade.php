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
                                       <td><h1 class="nk-block-title">Add REDEMPTION CODE</h1></td>
                                       <td></td>
                                    </tr>
                                 </tbody></table>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                 <form method="post" enctype="multipart/form-data" action="/AppleRedemptionCodes/Add" novalidate="novalidate">
                            
                            

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label class="" for="Attachment">Attachment</label>
                                    <input class="form-control input-file text-box single-line" data-val="true" data-val-required="The Attachment field is required." id="Attachment" name="Attachment" type="file">
                                    <small><i> Apple Redemption code excel file <br> Max Size: 10MB</i></small><br>
                                    <span class="field-validation-valid text-danger" data-valmsg-for="Attachment" data-valmsg-replace="true"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <a class="btn btn-light waves-effect waves-light" href="/AppleRedemptionCodes">Cancel</a>
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                            </div>
                        <input name="__RequestVerificationToken" type="hidden" value="CfDJ8OIbn6D8JvhDrTtpy6h3ZO8ZX8uYuPBQZ8zubZJy_-MezhgJj1JFBDyhPmPO4T-uGs1YhDyDYa1LmjA0gBjzfuzl24dRv6IQUXZS2Seto3hIm0sG8dHNiG7P4kn5r6HdxX_a44OItPvl1ng0eJFX8W-c_Ar-1YYWgVyL9eSXjbDX2yHgsumTMIvuLx-5BrBYaw"></form>
                                    
                                 </div>
                              </div>
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>


@endsection
