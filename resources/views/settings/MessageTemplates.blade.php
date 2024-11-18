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
                       Message Templates
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Setting</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Message Templates</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card">
                  <div class="card-body">
                     <table class="datatable-init table" data-nk-container="table-responsive">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Title</th>
                              <th>Content</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>1</td>
                              <td>Student Invoice First Notification</td>
                              <td>Dear Parents,
                                 Your SifuTutor invoice for [MONTHYEAR] is now ready. You can view and pay your bills online at [INVOICELINK]
                                 Total Amount : [TOTALAMOUNT]
                                 Alternatively, you can make payment to account no :
                                 MAYBANK:
                                 Sifu Edu &amp; Learning Sdn Bhd
                                 5621 1551 6678
                                 Please send proof of payment to www.wasap.my/60146037500
                                 If you have any enquiry, kindly call or Whatsapp 014-603 7500.
                                 Thank you.
                                 -SifuTutor Management Team
                                 [AUTOMATED MESSAGE - DO NOT REPLY]
                              </td>
                              <td>
                                 <a class="dtable-cbtn bt-edit dtb-tooltip" dtb-tooltip="Edit" href="/MessageTemplates/Details/1?returnUrl=%2FMessageTemplates%3Fpage%3D1%26sortOrder%3Dcreated"><i class="fa fa-edit"></i> </a>
                                 <a class="dtable-cbtn bt-delete dtb-tooltip" dtb-tooltip="Delete" href="/MessageTemplates/Edit/1?returnUrl=%2FMessageTemplates%3Fpage%3D1%26sortOrder%3Dcreated"><i class="fa fa-trash"></i> </a>
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