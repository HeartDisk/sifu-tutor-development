@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="nk-block-title">Add Tutor Voucher</h2>
                                <nav>
                                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Tutor Voucher</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add Tutor Voucher
                                        </li>
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

                                    <form method="POST" action="{{url('submit-tutor-voucher')}}">
                                        @csrf
                                        <div class="row g-3">

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Voucher Type</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-control" name="type">
                                                            <option value="">Select option</option>
                                                            <option value="receiving">Receiving</option>
                                                            <option value="payment">Payment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Tutor</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-control" name="tutor_id">
                                                            <option value="">Select tutor</option>
                                                            @foreach($tutors as $tutor)
                                                                <option
                                                                    value="{{$tutor->id}}">{{$tutor->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Date</label>
                                                    <div class="form-control-wrap">
                                                        <input type="date" name="date" class="form-control"
                                                               id="invoice_date">
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="col-lg-3">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="firstname" class="form-label">Reference Number</label>--}}
{{--                                                    <div class="form-control-wrap"><input readonly type="text"--}}
{{--                                                                                          name="reference_no"--}}
{{--                                                                                          class="form-control"--}}
{{--                                                                                          value="@php echo 'CRV-'.date('dis'); @endphp"--}}
{{--                                                                                          id="reference"></div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="firstname" class="form-label">Remarks</label>
                                                    <div class="form-control-wrap"><textarea name="note"
                                                                                             class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="nk-block-head">
                                                    <div class="nk-block-head-between flex-wrap">
                                                        <div class="nk-block-head-content">
                                                            <h3 class="nk-block-title">VOUCHER ITEMS</h3>
                                                        </div>
                                                        <div class="nk-block-head-content pt-5">
                                                            <ul class="d-flex">
                                                                <li>
                                                                    <button class="btn btn-md btn-success" id="addBtn"
                                                                            type="button">Add New Row
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Quantity</th>
                                                            <th>Unit Price</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbody">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-lg-2">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            var rowIdx = 0;
            $('#addBtn').on('click', function () {
                $('#tbody').append(`<tr id="R${++rowIdx}">
         <td class="row-index text-center"><textarea required class="form-control sifu-descr" id="description${rowIdx}" name="description[]"></textarea></td>
         <td class="row-index text-center"><input required type="text" class="form-control" id="quantity{rowIdx}" name="quantity[]"/></td>
         <td class="row-index text-center"><input required type="text" class="form-control" id="price{rowIdx}" name="price[]"/></td>
         <td class="text-center"><button style="background-color:#2e314a; color:#fff" class="btn remove" type="button">Remove</button></td>
          </tr>`);
            });
            $('#tbody').on('click', '.remove', function () {
                var child = $(this).closest('tr').nextAll();
                child.each(function () {
                    var id = $(this).attr('id');
                    var idx = $(this).children('.row-index').children('p');
                    var dig = parseInt(id.substring(1));
                    idx.html(`Row ${dig - 1}`);
                    $(this).attr('id', `R${dig - 1}`);
                });
                $(this).closest('tr').remove();
                rowIdx--;
            });
        });


    </script>
@endsection
