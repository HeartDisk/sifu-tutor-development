@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Add Creditor Payment</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Creditor Payment</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Creditor Payment</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     @if (\Session::has('success'))
                     <div class="alert alert-success">
                        <ul>
                           <li>{!! \Session::get('success') !!}</li>
                        </ul>
                     </div>
                     @endif
                     @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     <div class="bio-block">
                        <form method="POST" action="{{route('submitCreditorPayment')}}" enctype="multipart/form-data">
                           @csrf
                           <div class="row g-3">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Creditor Name</label>
                                    <div class="form-control-wrap"><input type="text" name="creditorName" class="form-control" id="creditorName"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payment Amount</label>
                                    <div class="form-control-wrap"><input type="text" name="amount" class="form-control" id="amount"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Payment Date</label>
                                    <div class="form-control-wrap"><input type="date" name="paymentDate" class="form-control" id="paymentDueDate"></div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Paying Account</label>
                                    <select class="form-control" name="chart_of_accounts">
                                       <option value="" selected>Please select</option>
                                       @foreach($accounts as $account)
                                       <option value="{{$account->id}}">{{$account->name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="firstname" class="form-label">Attachment</label>
                                    <div class="form-control-wrap"><input type="file" name="attachmentFile" class="form-control" id="attachment"></div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-2">
                                    <button
                                       class="btn btn-primary" type="submit">Submit
                                    </button>
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
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
   $(document).ready(function () {
       $(document).on('click', 'li', function () {
           $('#user').val($(this).text());
           $('#userList').fadeOut();
       });
   });
</script>
@endsection