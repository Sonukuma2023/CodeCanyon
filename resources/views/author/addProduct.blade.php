@extends('author.layouts.master')

@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" method="POST" action="{{ route('author.storeproduct') }}" enctype="multipart/form-data">
                @csrf
                <p class="card-description">Product info</p>

                <!-- Product Name -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" placeholder="Product Name" value="{{ old('name') }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="description" placeholder="Product Description" rows="4">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Category -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="category_id" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Thumbnail -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Upload Thumbnail <small>(JPEG or PNG)</small></label>
                            <div class="col-sm-9">
                                <input type="file" class="file-upload-default">
                                <div class="input-group">
                                    <input type="file" name="thumbnail" class="form-control file-upload-info" placeholder="Upload Thumbnail">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inline Preview -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Inline Preview Image <small>(JPEG)</small></label>
                            <div class="col-sm-9">
                                <input type="file" class="file-upload-default">
                                <div class="input-group">
                                    <input type="file" name="inline_preview" class="form-control file-upload-info" placeholder="Inline Preview">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Files -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Main Files <small>(ZIP file of images)</small></label>
                            <div class="col-sm-9">
                                <input type="file" class="file-upload-default" multiple>
                                <div class="input-group">
                                    <input type="file" name="main_files[]" class="form-control file-upload-info" placeholder="Main Files">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Preview Screenshots <small>(ZIP file of images)</small></label>
                            <div class="col-sm-9">
                                <input type="file" class="file-upload-default" multiple>
                                <div class="input-group">
                                    <input type="file" name="preview[]" class="form-control file-upload-info" placeholder="Preview">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Optional Live Preview <small>(ZIP file of images)</small></label>
                            <div class="col-sm-9">
                                <input type="file" class="file-upload-default" multiple>
                                <div class="input-group">
                                    <input type="file" name="live_preview[]" class="form-control file-upload-info" placeholder="Live Preview">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular License Price -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Set Price <small>(Regular License)</small></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" name="regular_license_price" placeholder="0.00" value="{{ old('regular_license_price') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extended License Price -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Set Price <small>(Extended License)</small></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" name="extended_license_price" placeholder="0.00" value="{{ old('extended_license_price') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
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
