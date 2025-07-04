@extends('admin.layouts.master')

@section('content')

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form class="forms-sample" method="POST" action="{{ route('admin.store') }}">
                @csrf         
                <p class="card-description">Category form</p>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="category" id="category" placeholder="Category Name">
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-rounded btn-fw">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
