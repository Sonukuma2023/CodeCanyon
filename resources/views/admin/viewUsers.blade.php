@extends('admin.layouts.master')

@section('content')
<h4 class="card-title">All Users</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td><span class="badge bg-info text-light" style="width: 100%;">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        @if($user->status === 'active')
                            <span class="badge bg-success text-light" style="width: 100%;">Active</span>
                        @else
                            <span class="badge bg-danger text-light" style="width: 100%;">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.edituser', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.deleteuser', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
						
						<a href="{{ route('admin.messagePage', $user->id) }}" class="btn btn-sm btn-warning">Messages</a>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
