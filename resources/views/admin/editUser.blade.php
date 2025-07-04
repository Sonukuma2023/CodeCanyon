@extends('admin.layouts.master')

@section('content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.updateuser', $user->id) }}" enctype="multipart/form-data">
                @csrf
                <h4 class="card-title">Edit User</h4>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" value="{{ $user->username }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" name="role">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="author" {{ $user->role == 'author' ? 'selected' : '' }}>Author</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Profile Image</label><br>
                    @if($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" width="60" height="60" style="border-radius: 50%; object-fit: cover;">
                    @endif
                    <input type="file" name="file" class="form-control mt-2">
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary btn-rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
