@extends('admin.layouts.master')
@section('title','Edit Coupon')
@section('content')

<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="mb-4 fw-bold">Edit Coupon</h3>

        <form id="editCouponForm">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label for="code" class="form-label">Coupon Code</label>
                    <input type="text" name="code" id="code" class="form-control form-control-lg" value="{{ $coupon->code }}">
                    <div class="text-danger error-code"></div>
                </div>

                <div class="col-md-6">
                    <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
                    <input type="number" name="discount_percentage" id="discount_percentage" class="form-control form-control-lg" value="{{ $coupon->discount_percentage }}">
                    <div class="text-danger error-discount_percentage"></div>
                </div>

                <div class="col-md-6">
                    <label for="minimum_order_amount" class="form-label">Minimum Order Amount (₹)</label>
                    <input type="number" name="minimum_order_amount" id="minimum_order_amount" class="form-control form-control-lg" value="{{ $coupon->minimum_order_amount }}">
                    <div class="text-danger error-minimum_order_amount"></div>
                </div>

                <div class="col-md-6">
                    <label for="usage_limit" class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" id="usage_limit" class="form-control form-control-lg" value="{{ $coupon->usage_limit }}">
                    <div class="text-danger error-usage_limit"></div>
                </div>

                <div class="col-md-6">
                    <label for="expires_at" class="form-label">Expiration Date</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" class="form-control form-control-lg" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '' }}">
                    <div class="text-danger error-expires_at"></div>
                </div>

                <div class="col-6 text-end mt-3 ">
                    <label for="active" class="form-label">Status</label>
                    <select name="active" class="form-select form-select-lg">
                        <option value="active" {{ $coupon->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ $coupon->status == 'expired' ? 'selected' : '' }}>Expire</option>
                    </select>
                    <div class="text-danger error-active"></div>
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-4">Update Coupon</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#editCouponForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();

        $.ajax({
            url: "{{ route('admin.updateCoupon', ['id' => $coupon->id]) }}",
            type: 'POST',
            data: formData,
            success: function (res) {
                toastr.success(res.message || 'Coupon updated successfully!');
                $('.text-danger').text('');
            },
            error: function (xhr) {
                $('.text-danger').text('');
                if (xhr.responseJSON?.errors) {
                    $.each(xhr.responseJSON.errors, function (key, messages) {
                        $(`.error-${key}`).text(messages[0]);
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
