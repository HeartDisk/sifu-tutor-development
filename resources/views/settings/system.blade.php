@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">System</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                   
                                 <div class="card-body">
                <div class="row">
                    <div class="col-md-12 pb-5">
                        <form method="post" enctype="multipart/form-data" action="/SystemSettings/System">
                            
                            
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <img src="https://portal.sifututor.my/Files/Logo/16-10-2020-17-07-054198.png" style="max-width: 120px;max-height: 120px;">
                                    <br>
                                    <label class="" for="LogoImage">Logo Image</label>
                                    <input accept="image/*" class="form-control input-file text-box single-line" id="LogoImage" name="LogoImage" type="file">
                                    <small><i>Recommended size: 190px x 63px</i></small> <br>
                                    <small><i>Supported extensions: jpg,jpeg.png. Max Size: 10MB </i></small>
                                    <span class="field-validation-valid text-danger" data-valmsg-for="LogoImage" data-valmsg-replace="true"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <img src="https://portal.sifututor.my/Files/Logo/19-10-2020-09-34-313300.png" style="max-width: 120px;max-height: 120px;">
                                    <br>
                                    <label class="" for="SidebarLogoImage">Sidebar Logo Image</label>
                                    <input accept="image/*" class="form-control input-file text-box single-line" id="SidebarLogoImage" name="SidebarLogoImage" type="file">
                                    <small><i>Recommended size: 170px x 56px </i></small> <br>
                                    <small><i>Supported extensions: jpg,jpeg.png. Max Size: 10MB </i></small>
                                    <span class="field-validation-valid text-danger" data-valmsg-for="SidebarLogoImage" data-valmsg-replace="true"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <img src="https://portal.sifututor.my/Files/Logo/13-03-2022-07-29-383955.png" style="max-width: 120px;max-height: 120px;">
                                    <br>
                                    <label class="" for="NakNgajiLogoImage">NakNgaji Logo Image</label>
                                    <input accept="image/*" class="form-control input-file text-box single-line" id="NakNgajiLogoImage" name="NakNgajiLogoImage" type="file">
                                    <small><i>Recommended size: 190px x 63px</i></small> <br>
                                    <small><i>Supported extensions: jpg,jpeg.png. Max Size: 10MB </i></small>
                                    <span class="field-validation-valid text-danger" data-valmsg-for="NakNgajiLogoImage" data-valmsg-replace="true"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3 form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="true" checked="" id="UseSimpleInvoice" name="UseSimpleInvoice">
                                        <label class="form-check-label" for="UseSimpleInvoice">
                                            Use Simple Sell Invoice
                                        </label>
                                        <span class="field-validation-valid text-danger" data-valmsg-for="UseSimpleInvoice" data-valmsg-replace="true"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3 form-group">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="false" id="UseSimpleStaffPayment" name="UseSimpleStaffPayment">
                                        <label class="form-check-label" for="UseSimpleStaffPayment">
                                            Use Simple Staff Payment
                                        </label>
                                        <span class="field-validation-valid text-danger" data-valmsg-for="UseSimpleStaffPayment" data-valmsg-replace="true"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Save Changes</button>
                            </div>
                        <input name="__RequestVerificationToken" type="hidden" value="CfDJ8OIbn6D8JvhDrTtpy6h3ZO_z9W0y9pz7u9Kr0aQidiyUDMtGe6D67N5Q27gbHIcyx6PrKSDSzPHnBtb2lyGNRdWWJSWhPaBR6h3CPlVAfUzRzr-VyQo6NqYI7BLmrBuh6mkR5Wu0-RWT0Ex--8SuOhvKEsdbqJl8bVXOBd3ZDEWNIV8tQmJX7bBdhhArDJSWNA"></form>
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
