@extends('admin.layouts.master')
@section('title', 'User Collections')

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
<h4 class="card-title">All User Collections</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Collection</th>
                <th>User</th>
                <th>Product(s)</th>
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
    function loadCollectionsData() {
        $.ajax({
            url: "{{ route('admin.fetchUserCollections') }}",
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="9" class="text-muted text-center">No carts found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.collection_name}</td>
                                <td>${item.user_name}</td>
                                <td>${item.product_name}</td>
                                <td>$ ${item.price}</td>
                                <td>${item.created_at}</td>
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

    $(document).ready(loadCollectionsData);

    $(document).on('click', '.remove-collection-product', function (e) {
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
                    loadCollectionsData();
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
