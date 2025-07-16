@extends('admin.layouts.master')
@section('title','Add Coupons')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Create New Coupon</h2>
    <form id="createCouponForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="code" class="form-label">Coupon Code</label>
                <input type="text" name="code" id="code" class="form-control"  placeholder="E.g. SUMMER25">
                <div class="text-danger error-code"></div>
            </div>

            <div class="col-md-6">
                <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" placeholder="Percentage (Optional)">
                <div class="text-danger error-discount_percentage"></div>
            </div>

            <div class="col-md-6">
                <label for="minimum_order_amount" class="form-label">Minimum Order Amount (₹)</label>
                <input type="number" name="minimum_order_amount" id="minimum_order_amount" class="form-control" step="0.01" value="0">
                <div class="text-danger error-minimum_order_amount"></div>
            </div>

            <div class="col-md-6">
                <label for="usage_limit" class="form-label">Usage Limit</label>
                <input type="number" name="usage_limit" id="usage_limit" class="form-control" placeholder="E.g. 100">
                <div class="text-danger error-usage_limit"></div>
            </div>

            <div class="col-md-6">
                <label for="expires_at" class="form-label">Expiration Date</label>
                <input type="datetime-local" name="expires_at" id="expires_at" class="form-control">
                <div class="text-danger error-expires_at"></div>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Status</label>
                <select name="active" class="form-select">
                    <option value="active" selected>Active</option>
                    <option value="expired">Inactive</option>
                </select>
                <div class="text-danger error-active"></div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">Save Coupon</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#createCouponForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();

        $.ajax({
            url: "{{ route('admin.storeCoupon') }}",
            type: "POST",
            data: formData,
            success: function (res) {
               toastr.success(res.message || 'Coupon created successfully!');
                form[0].reset();
                $('.text-danger').text('');
            },
            error: function (xhr) {
                $('.text-danger').text('');
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, messages) {
                        const errorText = messages[0]; 
                        $(`.error-${key}`).text(errorText);
                    });
                } else {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            }
        });
    });
});
</script>
@endsection
