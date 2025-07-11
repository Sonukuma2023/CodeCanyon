@extends('admin.layouts.master')
@section('title', 'Edit Cart')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="mdi mdi-cart-outline me-2"></i> Edit Cart</h5>
            <a href="{{ route('admin.usersCartPage') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to Cart List
            </a>
        </div>

        <div id="response-message"></div>

        <form id="editCartForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="cart_id" value="{{ $cart->id }}">

            <div class="card-body row g-4">

                <div class="col-md-6">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $cart->user->name ?? 'Guest' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Product</label>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $cart->product->name ?? 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control bg-white text-dark" value="{{ old('quantity', $cart->quantity) }}" required min="1">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price ($)</label>
                    <input type="text" class="form-control bg-white text-dark" value="{{ number_format($cart->price, 2) }}" disabled>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-content-save"></i> Save Changes
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#editCartForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const cartId = $("input[name='cart_id']").val();
        const formData = {
            quantity: $('#quantity').val(),
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };
        $.ajax({
            url: "{{ route('admin.updateUserCarts', $cart->id) }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#response-message').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message || 'Cart updated successfully.'}
                    </div>
                `);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors || {};
                let errorList = '';
                Object.keys(errors).forEach(key => {
                    errorList += `<li>${errors[key][0]}</li>`;
                });

                $('#response-message').html(`
                    <div class="alert alert-danger">
                        <ul class="mb-0">${errorList}</ul>
                    </div>
                `);
            }
        });
    });
</script>
@endsection
