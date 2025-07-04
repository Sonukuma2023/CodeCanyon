@extends('author.layouts.master')

@section('content')

<div class="col-12 grid-margin stretch-card">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Review</th>
                    <th>By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $key => $review)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $review->product->name ?? 'N/A' }}</td>
                        <td>{{ $review->review }}</td>
                        <td>{{ $review->user->name ?? 'Unknown User' }}</td>
                        <td>
                            @if($review->status == 'pending')
                                <span class="badge bg-warning text-light">Pending</span>
                            @elseif($review->status == 'approved')
                                <span class="badge bg-success text-light">Approved</span>
                            @else
                                <span class="badge bg-danger text-light">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($review->status == 'pending')
                                <a href="{{ route('author.approve', $review->id) }}" class="btn btn-success">Approve</a>
                                <a href="{{ route('author.reject', $review->id) }}" class="btn btn-danger">Reject</a>
                            @elseif($review->status == 'rejected')
                                <a href="{{ route('author.approve', $review->id) }}" class="btn btn-success">Approve</a>
                            @else
                                <a href="{{ route('author.reject', $review->id) }}" class="btn btn-danger">Reject</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


    </div>
</div>

@endsection