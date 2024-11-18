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
                        Chart of Account
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Account Section</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Chart of Account</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="{{url('/financialReport/submitAccount')}}">
                        @csrf
                        <div class="row">
                           <div class="col-md-4 mb-3">
                              <div class="form-group">
                                 <label class="form-label">Account No *</label>
                                 <input type="text" id="account_no" name="account_no" required="" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Account Type</label>
                                 <select id="account_type" name="type" class="form-control" required="">
                                    <option value=""> ---Select Account Type---</option>
                                    <option value="REVENUE">REVENUE</option>
                                    <option value="EXPENSES">EXPENSES</option>
                                    <option value="EQUITY">EQUITY</option>
                                    <option value="ASSETS">ASSETS</option>
                                    <option value="LIABILITY">LIABILITY</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Category</label>
                                 <select id="category" name="category" class="form-control" required="">
                                    <option value="">---Select Category---</option>
                                    @foreach($categories as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Sub Category</label>
                                 <select id="sub_category" name="sub_category" class="form-control">
                                    <option value="">---Select Sub Category---</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Name *</label>
                                 <input type="text" name="name" required="" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label class="form-label">Initial Balance</label>
                                 <input type="number" name="initial_balance" step="any" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label class="form-label">Note</label>
                                 <textarea name="note" rows="3" class="form-control sifu-descr"></textarea>
                              </div>
                           </div>
                           <div class="col-md-2">
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                           </div>
                        </div>
                     </form>
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>S.No</th>
                              <th>Category</th>
                              <th>Sub Category</th>
                              <th>Account Name</th>
                              <th>Account Type</th>
                              <th>Opening Balance</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($accountList as $key=>$row)
                           <tr>
                              <td>{{$key+1}}</td>
                              <td>{{isset($row->category)?$row->category->name:""}}</td>
                              <td>{{isset($row->subCategory)?$row->subCategory->name:""}}</td>
                              <td>{{$row->name}}</td>
                              <td>{{$row->type}}</td>
                              <td>{{$row->initial_balance}}</td>
                              <td>
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="{{url("/edit_account")."/".$row->id}}"> <i class="fa fa-edit"></i> </a>
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" onclick="return confirm('Are you sure you want to delete this?');" href="{{url("/delete_account")."/".$row->id}}"><i class="danger fa fa-trash"></i></a>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
   
       $('select[name="account"]').on('change', function () {
           var id = $(this).val();
           $.ajax({
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: "{{ url('account_type') }}/" + id,
           })
               .done(function (data) {
                   var count_account_type = jQuery.parseJSON(data);
                   var account_type;
                   switch (id) {
                       case("Income"):
                           $('#account_no').val("");
                           account_type = 'IN-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Expense"):
                           $('#account_no').val("");
                           account_type = 'EX-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Equity"):
                           $('#account_no').val("");
                           account_type = 'EQ-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Current-asset"):
                           $('#account_no').val("");
                           account_type = 'CA-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Non-current-asset"):
                           $('#account_no').val("");
                           account_type = 'NCA-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Current-liabilities"):
                           $('#account_no').val("");
                           account_type = 'CL-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Non-current-liabilities"):
                           $('#account_no').val("");
                           account_type = 'NCL-' + String(count_account_type).padStart(3, '0');
                           break;
                       case("Cost-of-sales"):
                           $('#account_no').val("");
                           account_type = 'COS-' + String(count_account_type).padStart(3, '0');
                           break;
                   }
    
                   $('#account_no').val(account_type);
               })
               .fail(function () {
               });
       });
   });
</script>
@endsection