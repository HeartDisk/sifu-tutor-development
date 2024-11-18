@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">MESSAGE TEMPLATES</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                        <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Content</th>
                                                <th scope="col" width="150">Action</th>
                                            </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                            <td scope="row">1</td>
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
[AUTOMATED MESSAGE - DO NOT REPLY]</td>
                                            <td>
                                                    <a href="/MessageTemplates/Details/1?returnUrl=%2FMessageTemplates%3Fpage%3D1%26sortOrder%3Dcreated" class="btn btn-outline-primary btn-table-action waves-effect waves-light"><span class="fa fa-eye"></span></a>
                                                    <a href="/MessageTemplates/Edit/1?returnUrl=%2FMessageTemplates%3Fpage%3D1%26sortOrder%3Dcreated" class="btn btn-outline-primary btn-table-action waves-effect waves-light" title="Edit"><span class="fa fa-edit"></span></a>
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
