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
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let wishlistTable = $('#wishlistTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.fetchWishlistItems') }}",
        columns: [
            { data: 'id' },
            { data: 'product_name' },
            { data: 'price' },
            { data: 'added_on' },
            { data: 'action' }
        ]
    });

    $(document).on('click', '.remove-item', function() {
        var wishlistId = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('user.deleteWishlist') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: wishlistId
                    },
                    success: function(res) {
                        if (res.success == true) {
                            Swal.fire({
                                title: "Deleted!",
                                text: res.message,
                                icon: "success"
                            });

                            wishlistTable.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong!",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection

