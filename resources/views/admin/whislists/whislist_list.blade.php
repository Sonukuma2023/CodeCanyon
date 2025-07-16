@extends('admin.layouts.master')
@section('title', 'Wishlist List')
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

<h4 class="card-title">All Wishlist Items</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Wishlist ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="wishlistTableBody">
            <tr>
                <td colspan="6" class="text-muted text-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function loadWishlistData() {
        $.ajax({
            url: "{{ route('admin.fetchWishlist') }}",
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="6" class="text-muted text-center">No wishlist items found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.id}</td>
                                <td>${item.user_name}</td>
                                <td>${item.product_name}</td>
                                <td>${item.created_at_human}</td>
                                <td>${item.actions}</td>
                            </tr>
                        `;
                    });
                }
                $('#wishlistTableBody').html(html);
            },
            error: function (xhr) {
                $('#wishlistTableBody').html('<tr><td colspan="6" class="text-danger text-center">Failed to load data.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadWishlistData);
</script>
@endsection
