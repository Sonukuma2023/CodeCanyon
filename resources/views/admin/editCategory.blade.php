@extends('admin.layouts.master')

@section('content')

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form class="forms-sample" method="POST" action="{{ route('admin.updatecategory', $category->id) }}">
                @csrf
                <p class="card-description">Edit Category</p>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Category Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="category" value="{{ $category->name }}">
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-rounded btn-fw">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
