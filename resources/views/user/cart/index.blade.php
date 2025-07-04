@extends('user.layouts.master')
@section('content')

    <h1>Your Cart</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th> <!-- Add a column for actions -->
                </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $id => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>${{ number_format($item['price'], 2) }}</td>
                <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                <td>
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                            <i class="fas fa-trash-alt me-1"></i> Remove
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach


            </tbody>
        </table>

        <p><strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}</p>
        <p><strong>Discount:</strong> ${{ number_format($discount, 2) }}</p>
        <p><strong>Tax:</strong> ${{ number_format($tax, 2) }}</p>
        <p><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
        <p><strong>Total Items:</strong> {{ $totalItems }}</p>
    @endif
@endsection
