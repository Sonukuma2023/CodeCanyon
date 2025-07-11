@extends('user.layouts.master')
@section('title', 'Community List')
@section('content')
<style>
    body {
        overflow-x: hidden !important;
    }

    .card-body {
        overflow-x: hidden !important;
    }
</style>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="eadercard-h bg-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-chat-dots-fill me-2 text-info"></i>
                    Community Complaints
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('user.communityPage') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Community
                    </a>
                </div>
            </div>
            <div class="card-body p-4 bg-light">
                <div id="communityList">
                    <div class="text-muted text-center">Loading complaints...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadCommunityList() {
        $.ajax({
            url: "{{ route('user.fetchCommunityList') }}",
            method: "GET",
            success: function (res) {
                let html = '';
                if (res.length === 0) {
                    html = `<div class="text-muted text-center">😕 No complaints submitted yet.</div>`;
                } else {
                    res.forEach(item => {
                        html += `
                            <div class="card mb-3 border-0 shadow-sm w-100">
                                <div class="card-body p-4 bg-white rounded">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-exclamation-circle-fill text-danger me-2"></i> ${item.complaint}
                                        </h6>
                                        <small class="text-muted text-end">
                                            <strong><i class="bi bi-clock me-1"></i> ${item.created_at_human}</strong>
                                        </small>
                                    </div>
                                    <p class="mb-2 text-secondary">${item.comment}</p>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                                        <strong><span class="text-muted small">${item.user?.name ?? 'Unknown User'}</span></strong>
                                    </div>
                                    ${item.admin_reply ? `
										<div class="mt-4 p-3 rounded shadow-sm border-start border-4 border-info bg-white">
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-person-badge-fill text-info me-2 fs-5"></i>
												<h6 class="mb-0 fw-bold text-info">Admin Reply</h6>
											</div>
											<p class="mb-0 text-dark">${item.admin_reply}</p>
										</div>
									` : ''}

									${item.developer_reply ? `
										<div class="mt-4 p-3 rounded shadow-sm border-start border-4 border-success bg-white">
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-code-slash text-success me-2 fs-5"></i>
												<h6 class="mb-0 fw-bold text-success">Developer Reply</h6>
											</div>
											<p class="mb-0 text-dark">${item.developer_reply}</p>
										</div>
									` : ''}
                                </div>
                            </div>
                        `;
                    });
                }
                $('#communityList').html(html);
            },
            error: function (xhr) {
                $('#communityList').html('<div class="text-danger text-center">⚠️ Failed to load complaints.</div>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadCommunityList);
	
	var channel = pusher.subscribe('my-channel');
	  channel.bind('CommunityCreated', function(data) {
		console.log('Received data:', data);
		loadCommunityList();
	});
</script>
@endsection


