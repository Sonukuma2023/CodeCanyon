@extends('developer.layouts.master')
@section('title', 'Manage Community')

@section('content')
<style>
    .table th,
    .table td {
        width: 16.66%;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal !important;
        vertical-align: top;
    }

    .table {
        table-layout: fixed;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>

<h4 class="card-title">Community Complaints</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Complaint</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="communityTableBody">
            <tr>
                <td colspan="6" class="text-muted text-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function loadCommunityData() {
        $.ajax({
            url: "{{ route('developer.fetchCommunities') }}",
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="6" class="text-muted text-center">No community complaints found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.user}</td>
                                <td>${item.complaint}</td>
                                <td>${item.comment}</td>
                                <td>${item.created_at_human}</td>
                                <td>${item.action}</td>
                            </tr>
                        `;
                    });
                }
                $('#communityTableBody').html(html);
            },
            error: function (xhr) {
                $('#communityTableBody').html('<tr><td colspan="6" class="text-danger text-center">Failed to load data.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadCommunityData);
	
	var channel = pusher.subscribe('my-channel');
	  channel.bind('CommunityCreated', function(data) {
		console.log('Received data:', data);
		loadCommunityData();
	});
</script>
@endsection
