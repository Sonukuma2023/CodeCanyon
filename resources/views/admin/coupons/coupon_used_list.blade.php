@extends('admin.layouts.master')
@section('title', 'Used Coupons')
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

<h4 class="card-title">Used Coupons List</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Coupon Code</th>
                <th>User</th>
                <th>Discount</th>
                <th>Order ID</th>
                <th>Order Total</th>
                <th>Used On</th>
            </tr>
        </thead>
        <tbody id="usedCouponsTableBody">
            <tr>
                <td colspan="7" class="text-muted text-center">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function loadUsedCoupons() {
        $.ajax({
            url: "{{ route('admin.fetchUsedCoupons') }}",
            type: 'GET',
            success: function (res) {
                let html = '';
                if (!res.data || res.data.length === 0) {
                    html = `<tr><td colspan="7" class="text-muted text-center">No used coupons found.</td></tr>`;
                } else {
                    res.data.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.coupon_code}</td>
                                <td>${item.user_name}</td>
                                <td>${item.discount_percent}%</td>
                                <td>#${item.order_id}</td>
                                <td>$${item.order_total}</td>
                                <td>${item.used_at}</td>
                            </tr>
                        `;
                    });
                }
                $('#usedCouponsTableBody').html(html);
            },
            error: function (xhr) {
                $('#usedCouponsTableBody').html('<tr><td colspan="7" class="text-danger text-center">Failed to load data.</td></tr>');
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(loadUsedCoupons);
</script>
@endsection
