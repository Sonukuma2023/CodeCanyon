@extends('user.layouts.master')
@section('title', 'Community Complaints')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom d-flex align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-megaphone-fill me-2 text-success"></i>
                    Submit a Complaint & Comment
                </h5>
            </div>
            <div class="card-body bg-light">

                <form id="complaintForm" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="complaintInput" class="form-label fw-semibold">Complaint Title</label>
                        <input type="text" name="complaint" id="complaintInput" class="form-control shadow-sm" placeholder="E.g. Unfair product pricing..." required>
                        <div class="invalid-feedback">Complaint title is required.</div>
                    </div>

                    <div class="mb-4">
                        <label for="commentInput" class="form-label fw-semibold">Your Comment</label>
                        <textarea name="comment" id="commentInput" rows="4" class="form-control shadow-sm" placeholder="Describe the issue in detail..." required></textarea>
                        <div class="invalid-feedback">Comment is required.</div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-send-fill me-1"></i> Submit
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
    $('#complaintForm').on('submit', function (e) {
        e.preventDefault();
		
		$('.invalid-feedback').text('').hide();
		$('input, textarea, select').removeClass('is-invalid');
		
        $.ajax({
            url: "{{ route('user.createCommunity') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                complaint: $('#complaintInput').val(),
                comment: $('#commentInput').val()
            },
            success: function (res) {
                toastr.success(res.message, 'Success');
                $('#complaintForm')[0].reset();
                $('#complaintForm').removeClass('was-validated');
            },
            error: function (xhr, status, error) {
				const errors = xhr.responseJSON?.errors;

				$('.invalid-feedback').text('').hide();
				$('input, textarea, select').removeClass('is-invalid');

				if (errors) {
					$.each(errors, function (field, messages) {
						const input = $(`[name="${field}"]`);
						input.addClass('is-invalid');
						input.siblings('.invalid-feedback').text(messages[0]).show();
					});
				} else {
					console.error('Unexpected error:', xhr.responseText);
				}
			}
        });
    });
</script>
@endsection
