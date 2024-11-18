@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Edit Chart of Account
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Chart of Account</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Edit Chart of Account</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="{{url('/update_account')}}">
                        @csrf
                        <input type="hidden" name="account_id" value="{{$data->id}}">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Account No *</label>
                                 <input type="text" value="{{$data->code}}" id="account_no" name="account_no" required="" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Account Type</label>
                                 <select id="account_type" name="type" class="form-control" required="">
                                    <option value=""> ---Select Account Type---</option>
                                    <option {{$data->type=="REVENUE"?"selected":""}} value="REVENUE">REVENUE</option>
                                    <option {{$data->type=="EXPENSES"?"selected":""}} value="EXPENSES">EXPENSES</option>
                                    <option {{$data->type=="EQUITY"?"selected":""}} value="EQUITY">EQUITY</option>
                                    <option {{$data->type=="ASSETS"?"selected":""}} value="ASSETS">ASSETS</option>
                                    <option {{$data->type=="LIABILITY"?"selected":""}} value="LIABILITY">LIABILITY</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Category</label>
                                 <select id="category" name="category" class="form-control" required="">
                                    <option value="">---Select Category---</option>
                                    @foreach($categories as $cat)
                                    <option
                                    {{$data->category_id==$cat->id?"selected":""}} value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Sub Category</label>
                                 <select id="sub_category" name="sub_category" class="form-control" required="">
                                    <option value="">---Select Sub Category---</option>
                                    <option
                                    {{$data->sub_category_id==$cat->id?"selected":""}} value="{{$data->sub_category_id}}">{{$data->Subcategory->name}}</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Name *</label>
                                 <input type="text" value="{{$data->name}}" name="name" required="" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Initial Balance</label>
                                 <input value="{{$data->initial_balance}}" type="number" name="initial_balance" step="any" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="form-label">Note</label>
                                 <textarea name="note" rows="3" class="form-control sifu-descr">{{$data->description}}</textarea>
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
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
<script>
   jQuery(document).ready(function ($) {
       $('select[name="category"]').on('change', function () {
           var id = $(this).val();
           $.ajax({
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: "{{ url('get-chart-of-accounts-subcategory-by-category') }}/" + id,
           })
               .done(function (data) {
                   var subcategorySelect = $('select[name="sub_category"]');
                   subcategorySelect.empty();
                   subcategorySelect.append('<option value="">Select Subcategory</option>');
                   $.each(data, function (key, value) {
                       subcategorySelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                   });
               })
               .fail(function () {
               });
       });
   });
</script>
@endsection