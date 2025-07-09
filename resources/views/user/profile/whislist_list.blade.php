@extends('user.layouts.master')
@section('title', 'My Wishlist')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Wishlist</h2>
    <div class="table-responsive">
        <table id="wishlistTable" class="table table-bordered table-striped" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Added On</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#wishlistTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.fetchWishlistItems') }}",
        columns: [
            { data: 'id', },
            { data: 'product_name', },
            { data: 'price', },
            { data: 'added_on', }
        ]
    });
});
</script>
@endsection
