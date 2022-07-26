@extends('layouts.dashboard_admin')

@section('content')
    <h3 class="p-4">Create product</h3>

    <div class="content pb-3">
        <form action="{{ route('admin.products.store', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card p-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="container-fluild form-group">
                                <label for="name">Product name *</label>
                                <input id="name" type="text" placeholder="Name"
                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                    required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="container-fluild form-group">
                                <label for="category_id">Product category</label>
                                <select id="category_id" name="category_id[]" multiple
                                    class="selectpicker form-control{{ $errors->has('category_id') ? 'is-invalid' : '' }}">
                                    @foreach ($product_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('category_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="container-fluild form-group">
                                <label for="price">Price *</label>
                                <input id="price" type="number" step="0.00001" placeholder="Price"
                                    class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price"
                                    required>

                                @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="container-fluild form-group">
                                <label for="quantity">Quantity *</label>
                                <input id="quantity" type="number" step="1" placeholder="Quantity"
                                    class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="quantity"
                                    required>

                                @if ($errors->has('quantity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="container-fluild form-group">
                                <label for="image">Product image *</label><br>
                                <input type="file" id="image"
                                    class="{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image"
                                    accept="image/*" required>

                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary" style="min-width:120px">Create</button>
            </div>
        </form>
    </div>
@endsection
