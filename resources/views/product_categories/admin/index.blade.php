@extends('layouts.dashboard_admin')

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Product categories</h4>
        </div>
        <div class="col-6 text-right">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                Create category
            </button>
        </div>
    </div>
    <hr>

    <!-- Create category modal -->
    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create product category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.product-categories.store', $client->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="container-fluild form-group">
                            <label for="name">Category name *</label>
                            <input id="name" type="text" placeholder="Name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" required>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-icon">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body table-responsive px-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category name</th>
                    <th>Total products</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($product_categories as $key => $product_category)
                    <tr>
                        <th scope="row">
                            {{ ($product_categories->currentpage() - 1) * $product_categories->perpage() + $key + 1 }}
                        </th>
                        <td>{{ $product_category->name }}</td>
                        <td>{{ $product_category->products->count() }}</td>
                        <td>
                            @if ($product_category->trashed())
                                Hidden
                            @else
                                Visible
                            @endif
                        </td>
                        <td>
                            <form
                                action="{{ route('admin.product-categories.toggle', [$client->id, $product_category->id]) }}"
                                method="POST">
                                @csrf
                                @method('put')
                                <button type="submit" class="btn btn-primary btn-icon">
                                    @if ($product_category->trashed())
                                        Show
                                    @else
                                        Hide
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-edit-{{ $product_category->id }}">Edit</button></td>
                        <td><button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-delete-{{ $product_category->id }}">Delete</button></td>
                    </tr>

                    <!-- Edit category modal -->
                    <div class="modal fade" id="modal-edit-{{ $product_category->id }}" tabindex="-1"
                        aria-labelledby="modalEditLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit product category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form
                                    action="{{ route('admin.product-categories.update', [$client->id, $product_category->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <div class="container-fluild form-group">
                                            <label for="name">Category name *</label>
                                            <input id="name" type="text" placeholder="Name"
                                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                name="name" value="{{ $product_category->name }}" required>

                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger btn-icon">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete category modal -->
                    <div class="modal fade" id="modal-delete-{{ $product_category->id }}" tabindex="-1"
                        aria-labelledby="modalDeleteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete product category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form
                                    action="{{ route('admin.product-categories.destroy', [$client->id, $product_category->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <div class="container-fluild form-group">
                                            <p>This action will delete this category and remove any assignment of this
                                                category from products. Are you sure you want to delete this category?</p>
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
    {{ $product_categories->links() }}
@endsection
