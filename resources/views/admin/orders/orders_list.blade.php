@extends('admin.layouts.master')
@section('title', 'Orders List')
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

<h4 class="card-title">All Orders</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="ordersTableBody">
            <tr>
                <td colspan="8" class="text-muted text-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function loadOrdersData() {
        $.ajax({
            url: "{{ route('admin.fetchOrders') }}", 
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="8" class="text-muted text-center">No orders found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.order_id}</td>
                                <td>${item.user_name}</td>
                                <td>${item.product_names}</td>
                                <td>${item.total}</td>
                                <td>${item.status}</td>
                                <td>${item.created_at_human}</td>
                                <td>${item.actions}</td>
                            </tr>
                        `;
                    });
                }
                $('#ordersTableBody').html(html);
            },
            error: function (xhr) {
                $('#ordersTableBody').html('<tr><td colspan="8" class="text-danger text-center">Failed to load orders.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadOrdersData);

</script>
@endsection
