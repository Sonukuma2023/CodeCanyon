@extends('author.layouts.master')

@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <form class="form-sample" method="POST" action="{{ route('author.updateproduct', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <p class="card-description">Edit Product Info</p>

                <!-- Product Name -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" placeholder="Product Name" value="{{ old('name', $product->name) }}" />
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
                                <textarea class="form-control" name="description" placeholder="Product Description" rows="4">{{ old('description', $product->description) }}</textarea>
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
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Thumbnail -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="col-form-label">Current Thumbnail:</label><br>
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" width="100">
                        @else
                            <span>No thumbnail uploaded</span>
                        @endif
                    </div>
                </div>

                <!-- Upload New Thumbnail -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Upload Thumbnail <small>(JPEG or PNG)</small></label>
                            <div class="col-sm-9">
                                <input type="file" name="thumbnail" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Inline Preview -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="col-form-label">Current Inline Preview:</label><br>
                        @if ($product->inline_preview)
                            <img src="{{ asset('storage/' . $product->inline_preview) }}" width="100">
                        @else
                            <span>No inline preview uploaded</span>
                        @endif
                    </div>
                </div>

                <!-- Upload New Inline Preview -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Inline Preview Image <small>(JPEG)</small></label>
                            <div class="col-sm-9">
                                <input type="file" name="inline_preview" class="form-control">
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
                                <input type="file" name="main_files[]" multiple class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Files -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Preview Screenshots <small>(ZIP file of images)</small></label>
                            <div class="col-sm-9">
                                <input type="file" name="preview[]" multiple class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Files -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Optional Live Preview <small>(ZIP file of images)</small></label>
                            <div class="col-sm-9">
                                <input type="file" name="live_preview[]" multiple class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Price -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Set Price <small>(Regular License)</small></label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" name="regular_license_price" class="form-control" value="{{ old('regular_license_price', $product->regular_license_price) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extended Price -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Set Price <small>(Extended License)</small></label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" name="extended_license_price" class="form-control" value="{{ old('extended_license_price', $product->extended_license_price) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success btn-rounded btn-fw">UPDATE</button>
                        <a href="{{ route('author.viewproduct') }}" class="btn btn-light btn-rounded btn-fw">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
