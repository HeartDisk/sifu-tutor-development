@extends('layouts.main')
@section('content')
    <div class="nk-content sifu-view-page">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    API Logs</h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">API Logs</li>
                                        </ol>
                                    </nav>
                            </div>
                        </div>
                    </div>
                <div class="nk-block">
                    <div class="card">
                        <div class="card-body">
                            <table class="datatable-init table" id="apiLogsTable" data-nk-container="table-responsive">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Method</th>
                                        <th>URL</th>
                                        <th>Body</th>
                                        <th>Status</th>
                                        <th>Response Content</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($apiLogs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ $log->method }}</td>
                                            <td>{{ $log->url }}</td>
                                            <td>
                                                <button 
                                                    class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#contentModal" 
                                                    onclick="showContent('{{ $log->body }}', 'Body')">View Body</button>
                                            </td>
                                            <td>{{ $log->status }}</td>
                                            <td>
                                                <button 
                                                    class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#contentModal" 
                                                    onclick="showContent('{{ $log->response_content }}', 'Response Content')">View Response</button>
                                            </td>
                                            <td>{{ $log->created_at }}</td>
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

<!-- Modal -->
<div class="modal fade" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentModalLabel">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with search enabled
    $('#apiLogsTable').DataTable({
        responsive: true,
        autoWidth: false,
        searching: true, // Enable the search functionality
    });
});
    
function showContent(content, title) {
    // Set the modal title
    document.getElementById('contentModalLabel').innerText = title;

    // Parse the JSON content
    let parsedContent;
    try {
        parsedContent = JSON.parse(content);
    } catch (e) {
        parsedContent = content;  // Fallback if parsing fails
    }

    // Format the content for display
    const formattedContent = JSON.stringify(parsedContent, null, 4);

    // Set the modal content
    document.getElementById('modalContent').innerHTML = '<pre>' + formattedContent + '</pre>';
}
</script>
@endsection
