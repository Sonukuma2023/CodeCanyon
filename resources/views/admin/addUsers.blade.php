@extends('admin.layouts.master')

@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" action="{{ route('admin.storeUser') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <p class="card-description">Personal info</p>

                <!-- Row 1: Name & Username -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Profile Image & Phone -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profile</label>
                            <div class="col-sm-9">
                                <input type="file" name="img[]" class="file-upload-default">
                                <div class="input-group">
                                    <input type="file" class="form-control file-upload-info" placeholder="Upload Image" name="file">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="phone" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Email & Role -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" />
                            </div>
                        </div>
                    </div> 
                </div>

                <!-- Row: Role & Status -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="role">
                                    <option value="user">User</option>
                                    <option value="author">Author</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Submit Button -->
                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-rounded btn-fw">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
