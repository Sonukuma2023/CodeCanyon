@extends('user.layouts.master')
@section('title', 'Order History')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Order History</h2>
    <div class="table-responsive">
        <table id="ordersTable" class="table table-bordered table-striped" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.fetchOrdersHistory') }}",
        columns: [
            { data: 'id' },
            { data: 'products' },
            { data: 'total' },
            { data: 'status' },
            { data: 'date' }
        ]
    });
});
</script>
@endsection
