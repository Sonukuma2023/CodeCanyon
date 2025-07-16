@extends('user.layouts.master')
@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Edit Profile</h2>

    <div id="response-message"></div>

    <form id="profileForm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            <div class="text-danger" id="error-name"></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{  $user->email }}">
            <div class="text-danger" id="error-email"></div>
        </div>


        <!-- Toggle Password Change -->
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="changePasswordSwitch">
            <label class="form-check-label" for="changePasswordSwitch">Change Password</label>
        </div>

        <!-- Password Fields (initially hidden) -->
        <div id="passwordFields" style="display: none;">
            <div class="mb-3">
                <label for="password" class="form-label">New Password <small>(optional)</small></label>
                <input type="password" name="password" class="form-control">
                <div class="text-danger" id="error-password"></div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();


        $('.text-danger').text('');
        $('#response-message').html('');

        $.ajax({
            url: "{{ route('user.ProfileUpdate') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#response-message').html(`
                    <div class="alert alert-success">${response.message}</div>
                `);
                form[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#error-' + key).text(value[0]);
                    });
                } else {
                    $('#response-message').html(`
                        <div class="alert alert-danger">Something went wrong!</div>
                    `);
                }
            }
        });
    });
});

$('#changePasswordSwitch').on('change', function () {
    if ($(this).is(':checked')) {
        $('#passwordFields').slideDown();
    } else {
        $('#passwordFields').slideUp();
        $('#passwordFields input').val('');
    }
});

</script>
@endsection
