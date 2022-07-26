@extends('layouts.dashboard_admin')

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Products</h4>
        </div>
        <div class="col-6 text-right">
            <a class="btn btn-primary" href="{{ route('admin.products.create', $client->id) }}">Create product</a>
        </div>
    </div>
    <hr>
    <div class="card-body table-responsive px-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <th scope="row">
                            {{ ($products->currentpage() - 1) * $products->perpage() + $key + 1 }}
                        </th>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td><img src="{{ asset('storage/product/image/' . $product->image) }}" height="40" alt="img">
                        </td>
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-show-{{ $product->id }}">View</button></td>
                        <td><button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-delete-{{ $product->id }}">Delete</button></td>
                    </tr>

                    <!-- Show product modal -->
                    <div class="modal fade" id="modal-show-{{ $product->id }}" tabindex="-1"
                        aria-labelledby="modalShowLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="showModalLabel">View product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <i>Product name</i>
                                            <div class="float-right">{{ $product->name }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <i>Product category/categories</i>
                                            @foreach ($product->product_categories as $category)
                                                <div class="m-1">
                                                    <div class="float-right bg-secondary px-1 m-1 rounded-2">
                                                        {{ $category->name }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </li>
                                        <li class="list-group-item">
                                            <i>Price</i>
                                            <div class="float-right">{{ $product->price }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <i>Quantity</i>
                                            <div class="float-right">{{ $product->quantity }}</div>
                                        </li>
                                        <i class="my-3">Image</i>
                                        <img src="{{ asset('storage/product/image/' . $product->image) }}" class="px-4"
                                            width="100%" alt="">
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                    <a href="{{ route('admin.products.edit', [$client->id, $product->id]) }}"
                                        class="btn btn-outline-primary">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete product modal -->
                    <div class="modal fade" id="modal-delete-{{ $product->id }}" tabindex="-1"
                        aria-labelledby="modalDeleteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.products.destroy', [$client->id, $product->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <div class="container-fluild form-group">
                                            <p>Are you sure you want to delete this product?</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger btn-icon">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>


    </div>
    {{ $products->links() }}
@endsection
