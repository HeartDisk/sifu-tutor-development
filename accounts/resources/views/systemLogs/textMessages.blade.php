@extends('layouts.main')

@section('content')

    <div class="nk-content">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-page-head">
                        <div class="nk-block-head-content">
                            <h1 class="nk-block-title">TEXT MESSAGES</h1>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-body">
                                <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                    <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Receipent No.</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Send Date Time</th>
                                        <th scope="col">Channel</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>{{$message->recipient}}</td>
                                            <td>{{$message->message}}</td>
                                            <td>{{$message->status}}</td>
                                            <td>{{$message->created_at}}</td>
                                            <td>SMS</td>
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

@endsection
