@extends('user.layouts.master')
@section('title', 'My Collections')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Product Collections</h2>
    <div class="table-responsive">
        <table id="collectionTable" class="table table-bordered table-striped" style="width:100%">
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
    let collectionTable = $('#collectionTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('user.fetchCollectionItems') }}",
        columns: [
            { data: 'id' },
            { data: 'product_name' },
            { data: 'price' },
            { data: 'added_on' },
            { data: 'action' }
        ]
    });

    $(document).on('click', '.remove-item', function() {
        let collectionProdId = $(this).data('id');
        let url = $(this).data('href');
        Swal.fire({
            title: "Are you sure?",
            text: "This will remove the product from your collection.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, remove it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: collectionProdId
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire("Removed!", res.message, "success");
                            collectionTable.ajax.reload(null, false);
                        } else {
                            Swal.fire("Error!", res.message, "error");
                        }
                    }
                });
            }
        });
    });
});
</script>
@endsection
