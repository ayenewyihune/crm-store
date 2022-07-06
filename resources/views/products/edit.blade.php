@extends('layouts.dashboard')

@section('content')
    <h3 class="p-4">Edit product</h3>

    <div class="content pb-3">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="card p-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="container-fluild form-group">
                                <label for="name">Product name *</label>
                                <input id="name" type="text" placeholder="Name"
                                    class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                    value="{{ $product->name }}" required>

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
                                        <option value="{{ $category->id }}"
                                            @if (in_array($category->id, $product->product_categories->pluck('id')->toArray())) selected='selected' @endif>
                                            {{ $category->name }}</option>
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
                                    value="{{ $product->price }}" required>

                                @if ($errors->has('price'))
                                    <span class="invalid-feedback" role="alert">
                                        <stfrong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="container-fluild form-group">
                                <label for="quantity">Quantity *</label>
                                <input id="quantity" type="number" step="1" placeholder="Quantity"
                                    class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}"
                                    name="quantity" value="{{ $product->quantity }}" required>

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
                                    accept="image/*">

                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <img src="{{ asset('storage/product/image/' . $product->image) }}" height="80"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-danger" style="min-width:120px">Update</button>
            </div>
        </form>
    </div>
@endsection
