@extends('user.layouts.master')

@section('content')

<div class="container py-5">

    @if ($orders->isEmpty())
        <div class="alert alert-info">
            You have no orders yet.
            <a href="{{ route('dashboard') }}" class="alert-link">Continue shopping</a>.
        </div>
    @else
        <div class="row g-4">
            <form action="{{ route('orders') }}" method="POST">
            @csrf
            @foreach ($orders as $order)
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Order ID:</strong> #{{ $order->id }}
                                <br>
                                <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <div>
                                @switch($order->status)
                                    @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info text-dark">Processing</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary text-capitalize">{{ $order->status }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-start mb-3">
                                    @if ($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                             class="rounded border me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             alt="{{ $item->product->name }}">
                                    @else
                                        <div class="bg-light border rounded me-3"
                                             style="width: 60px; height: 60px;"></div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $item->name }} × {{ $item->quantity }}</div>
                                        <div class="text-muted">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total:</strong> ${{ number_format($order->total, 2) }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Payment Status:</strong>
                                    @if ($order->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($order->payment_status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </form>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-shop"></i> Continue Shopping
        </a>
    </div>
</div>
@endsection
