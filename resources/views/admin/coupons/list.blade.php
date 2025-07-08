@extends('admin.layouts.master')
@section('title', 'Coupons List')
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

<h4 class="card-title">All Coupons</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Discount Amount</th>
                <th>Discount Percentage</th>
                <th>Usage Limit</th>
                <th>Minimum Order Amount</th>
                <th>Expires At</th>
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
            url: "{{ route('admin.fetchCoupons') }}", 
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
                                <td>${item.code}</td>
                                <td>${item.discount_amount ?? '-'}</td>
                                <td>${item.discount_percentage ?? '-'}</td>
                                <td>${item.usage_limit ?? '-'}</td>
                                <td>${item.minimum_order_amount ?? '-'}</td>
                                <td>${item.expires_at}</td>
                                <td>${item.status}</td>
                                <td>${item.created_at}</td>
                                <td>${item.actions}</td>
                            </tr>
                        `;
                    });
                }
                $('#ordersTableBody').html(html);
            },
            error: function (xhr) {
                $('#ordersTableBody').html('<tr><td colspan="8" class="text-danger text-center">Failed to load coupons.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(loadOrdersData);
</script>
@endsection
