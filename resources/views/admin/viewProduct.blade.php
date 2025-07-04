@extends('admin.layouts.master')

@section('content')
<h4 class="card-title">All Products</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Regular Price</th>
                <th>Extended Price</th>
                <th>Thumbnail</th>
                <th>Inline Preview</th>
                <th>Main Files</th>
                <th>Preview Images</th>
                <th>Live Preview</th>
                <th>Role</th>                
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>${{ number_format($product->regular_license_price, 2) }}</td>
                    <td>${{ number_format($product->extended_license_price, 2) }}</td>
                    <td>
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" width="80" alt="Thumbnail">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($product->inline_preview)
                            <img src="{{ asset('storage/' . $product->inline_preview) }}" width="80" alt="Inline Preview">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $mainFiles = is_array($product->main_files) ? $product->main_files : json_decode($product->main_files, true);
                        @endphp

                        @if($mainFiles)
                            @foreach($mainFiles as $file)
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Download</a><br>
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $previewFiles = is_array($product->preview) ? $product->preview : json_decode($product->preview, true);
                        @endphp

                        @if($previewFiles)
                            @foreach($previewFiles as $file)
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Download</a><br>
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $livePreviewFiles = is_array($product->live_preview) ? $product->live_preview : json_decode($product->live_preview, true);
                        @endphp

                        @if($livePreviewFiles)
                            @foreach($livePreviewFiles as $file)
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Download</a><br>
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($product->user_id == 1)
                            <span class="badge bg-success text-light" style="width:100%;">Admin</span>
                        @elseif ($product->user_id == 2)
                            <span class="badge bg-primary text-light" style="width:100%;">Author</span>
                        @else
                            <span class="badge bg-secondary">User #{{ $product->user_id }}</span>
                        @endif
                    </td>                    
                    <td><span class="badge bg-info text-light" style="width: 100%; text-transform: capitalize;">{{ $product->status ?? 'N/A' }}</span></td>
                    <td>
                        <a href="{{ route('admin.editproduct', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.deleteproduct', $product->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
