@extends('developer.layouts.master')
@section('title', 'Reply to Complaint')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-reply-fill me-2 text-info"></i>
                    Reply to Complaint
                </h5>
                <a href="#" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body bg-light">
                <div class="mb-3">
					<label class="form-label fw-semibold">Complaint Title</label>
					<input type="text" class="form-control bg-white text-dark" value="{{ $community->complaint }}" readonly style="background-color: white; color: black;">
				</div>

				<div class="mb-3">
					<label class="form-label fw-semibold">Complaint Comment</label>
					<textarea class="form-control bg-white text-dark" rows="5" readonly style="background-color: white; color: black;">{{ $community->comment }}</textarea>
				</div>

                <form id="developerReplyForm">
                    @csrf
                    <div class="mb-3">
                        <label for="developer_reply" class="form-label fw-semibold">Your Reply</label>
                        <textarea name="developer_reply" id="developer_reply" rows="5" class="form-control shadow-sm" placeholder="Type your response...">{{ old('reply', $community->developer_reply) }}</textarea>
                        <div class="text-danger mt-1 small d-none" id="replyError"></div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-send-fill me-1"></i> Submit Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#developerReplyForm').on('submit', function (e) {
        e.preventDefault();

        $('#replyError').addClass('d-none').text('');

        $.ajax({
			url: "{{ route('developer.replyCommunity', ['id' => $community->id]) }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                developer_reply: $('#developer_reply').val()
            },
            success: function (res) {
                toastr.success(res.message);
                $('#reply').val('');
            },
            error: function (xhr) {
				if (xhr.status === 422) {
					const errors = xhr.responseJSON.errors;
					if (errors.developer_reply) {
						$('#replyError').removeClass('d-none').text(errors.developer_reply[0]);
					}
				} else {
					console.error(xhr.responseText);
				}
			}
        });
    });
</script>
@endsection
