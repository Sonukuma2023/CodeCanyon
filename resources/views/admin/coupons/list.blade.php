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
    .swal2-icon {
        margin: 0 auto !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    .swal2-icon.swal2-warning {
        border-color: #ffc107 !important;
        color: #ffc107 !important;
    }

    .swal2-popup {
        padding: 2.5rem 2rem;
    }

    .swal2-title {
        margin-top: 1rem;
    }

    .swal2-actions .btn {
        font-size: 0.95rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }
</style>

<h4 class="card-title">All Coupons</h4>
<div class="table-responsive">
    <table class="table table-bordered align-middle text-start w-100">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Code</th>
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
    function loadCouponsData() {
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
    $(document).ready(loadCouponsData);

    $(document).on('click', '.remove-coupon', function (e) {
        e.preventDefault();
        
        const orderId = $(this).data('id');
        const deleteUrl = $(this).data('href');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the order!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            Swal.fire('Deleted!', res.message, 'success');
                            loadCouponsData(); // reload the table if needed
                        } else {
                            Swal.fire('Error', res.message || 'Failed to delete.', 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection
