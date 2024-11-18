@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">MY INVOICES</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                       <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Reference No.</th>
                                            <th scope="col">Invoice Date</th>
                                            <th scope="col">Payment Due Date</th>
                                            <th scope="col">Total Price</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                       </thead>
                                       <tbody>
                                       <tr>
                                            <th scope="row">1</th>
                                            <td>FINV001272</td>
                                            <td>01/06/2022</td>
                                            <td>07/06/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>FINV001283</td>
                                            <td>01/07/2022</td>
                                            <td>07/07/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>FINV001285</td>
                                            <td>01/08/2022</td>
                                            <td>07/08/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>FINV001287</td>
                                            <td>01/09/2022</td>
                                            <td>07/09/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>FINV001295</td>
                                            <td>01/10/2022</td>
                                            <td>07/10/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>FINV001303</td>
                                            <td>01/11/2022</td>
                                            <td>07/11/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>FINV001302</td>
                                            <td>01/12/2022</td>
                                            <td>07/12/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>FINV001310</td>
                                            <td>01/01/2023</td>
                                            <td>07/01/2023</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                            Paid
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td>FINV001262</td>
                                            <td>01/05/2022</td>
                                            <td>07/05/2022</td>
                                            <td>RM 4,710.00</td>
                                            <td>
                                                        Paid
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
