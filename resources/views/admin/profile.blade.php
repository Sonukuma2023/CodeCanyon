@extends('admin.layouts.master')
@section('title', 'Admin Profile')
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Update Profile</h4>

        <form id="adminProfileForm">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input name="name" id="name" type="text" class="form-control" value="{{ $user->name }}">
				 <div class="invalid-feedback" id="error-name"></div>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input name="email" id="email" type="email" class="form-control" value="{{ $user->email }}">
				<div class="invalid-feedback" id="error-email"></div>
            </div>

            <div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" id="changePasswordToggle">
				<label class="form-check-label" for="changePasswordToggle">Change Password</label>
			</div>

			<div id="passwordFields" style="display: none;">
				<div class="mb-3">
					<label>New Password</label>
					<input name="password" id="password" type="password" class="form-control">
					<div class="invalid-feedback" id="error-password"></div>
				</div>

				<div class="mb-3">
					<label>Confirm Password</label>
					<input name="password_confirmation" id="password_confirmation" type="password" class="form-control">
					<div class="invalid-feedback" id="error-password_confirmation"></div>
				</div>
			</div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <div id="formAlert" class="mt-3"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#changePasswordToggle').on('change', function () {
        if ($(this).is(':checked')) {
            $('#passwordFields').slideDown();
        } else {
            $('#passwordFields').slideUp();
        }
    });

    $('#adminProfileForm').on('submit', function (e) {
        e.preventDefault();
        $('#formAlert').html('');
		$('.invalid-feedback').html('');
		$('.form-control').removeClass('is-invalid');
		
        $.ajax({
            url: "{{ route('admin.updateProfile') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#formAlert').html(`<div class="alert alert-success">${response.message}</div>`);
            },
            error: function (xhr) {
				$('.invalid-feedback').html('');
				$('.form-control').removeClass('is-invalid');

				if (xhr.status === 422) {
					let errors = xhr.responseJSON.errors;
					$.each(errors, function (key, messages) {
						$(`#${key}`).addClass('is-invalid');
						$(`#error-${key}`).html(messages[0]);
					});
				} else {
					$('#formAlert').html('<div class="alert alert-danger">Something went wrong!</div>');
				}
			}
        });
    });
});
</script>
@endsection
