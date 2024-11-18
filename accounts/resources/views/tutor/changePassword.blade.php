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
                <td>
                  <h1 class="nk-block-title">Change Password Profile</h1>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="nk-block">
          <div class="card">
            <div class="card-body">
                <form action="{{route('submitChangePassword')}}" method="POST">
                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <label>Current Password</label>
                      <input type="text" name="currentPassword" value="" class="form-control" />
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6 mb-4">
                      <label>New Password</label>
                      <input type="text" name="newPassword" value="" class="form-control" />
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                          <label>Confirm New Password</label>
                          <input type="text" name="confirmNewPassword" value="" class="form-control" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                            <br/>
                          <input type="submit" name="submit" value="submit " class="btn btn-primary" />
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