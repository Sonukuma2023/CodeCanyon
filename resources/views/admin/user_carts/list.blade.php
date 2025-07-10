@extends('admin.layouts.master')
@section('title', 'Carts List')

@section('content')
<style>
    .table th,
    .table td {
        word-wrap: break-word;
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

<h4 class="card-title">All User Carts</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Cart ID</th>
                <th>User</th>
                <th>Product(s)</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartsTableBody">
            <tr>
                <td colspan="9" class="text-muted text-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function loadCartsData() {
        $.ajax({
            url: "{{ route('admin.fetchUserCarts') }}", // Make sure this route exists
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="9" class="text-muted text-center">No carts found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.cart_id}</td>
                                <td>${item.user_name}</td>
                                <td>${item.product_names}</td>
                                <td>${item.total_quantity}</td>
                                <td>${item.price}</td>
                                <td>${item.created_at_human}</td>
                                <td>${item.actions}</td>
                            </tr>
                        `;
                    });
                }
                $('#cartsTableBody').html(html);
            },
            error: function (xhr) {
                $('#cartsTableBody').html('<tr><td colspan="9" class="text-danger text-center">Failed to load carts.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadCartsData);

    $(document).on('click', '.remove-cart', function (e) {
    e.preventDefault();
    
    const button = $(this);
    let cartId = $(this).data('id');
    const deleteUrl = button.data('href');

    Swal.fire({
        title: 'Are you sure?',
        text: "This cart item will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Cart deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    loadCartsData();
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong while deleting the cart.'
                    });
                }
            });
        }
    });
});
</script>
@endsection
