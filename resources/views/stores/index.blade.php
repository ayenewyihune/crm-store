@extends('layouts.dashboard')

@section('content')
    <div class="card-body table-responsive px-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store number</th>
                    <th>Owner</th>
                    <th>Total product categories</th>
                    <th>Total products</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $key => $store)
                    <tr>
                        <th scope="row">{{ ($stores->currentpage() - 1) * $stores->perpage() + $key + 1 }}</th>
                        <td>{{ $store->user->id }}</td>
                        <td>{{ $store->user->name }}</td>
                        <td>{{ $store->product_categories->count() }}</td>
                        <td>{{ $store->products->count() }}</td>
                        <td>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modal-deactivate-{{ $store->id }}">DEACTIVATE</button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade modal-danger" id="modal-deactivate-{{ $store->id }}" tabindex="-1"
                        aria-labelledby="modalDeactivate" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelDeactivate">Deactivate</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to deactivate this store?</p>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger btn-icon">Deactivate</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>


    </div>
    {{ $stores->links() }}
@endsection
