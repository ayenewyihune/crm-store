@extends('layouts.admin')

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
                        <td>{{ $store->user->product_categories->count() }}</td>
                        <td>{{ $store->user->products->count() }}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-restore-{{ $store->id }}">RESTORE</button>
                        </td>
                    </tr>

                    <!-- Modal restore -->
                    <div class="modal fade modal-primary" id="modal-restore-{{ $store->id }}" tabindex="-1"
                        aria-labelledby="modalRestore" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="labelRestore">Restore</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to restore this store?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.stores.restore', $store->id) }}">Restore</a>
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
