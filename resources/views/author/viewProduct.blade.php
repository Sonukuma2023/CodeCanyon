@extends('author.layouts.master')

@section('content')
<div class="table-responsive">

    {{-- Approved Products Table --}}
    @if($products->where('status', 'approved')->count() > 0)
        <h5>Approved Products</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Regular Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products->where('status', 'approved') as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>${{ number_format($product->regular_license_price, 2) }}</td>
                            <td><span class="badge bg-success text-light">Approved</span></td>
                            <td>
                                <form action="{{ route('author.deleteproduct', $product->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pending Products Table --}}
    @if($products->where('status', 'pending')->count() > 0)
        <h5 class="mt-4">Queued for Review</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products->where('status', 'pending') as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $product->status_message ?? 'Queued for Review' }}</span></td>
                            <td>
                                <form action="{{ route('author.deleteproduct', $product->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- If no products at all --}}
    @if($products->count() == 0)
        <div class="text-center mt-4">
            <p>No products found.</p>
        </div>
    @endif
</div>
@endsection
