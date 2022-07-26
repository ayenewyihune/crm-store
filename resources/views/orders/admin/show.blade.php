@extends('layouts.dashboard_admin')

@section('content')
    <div class="container-fluid">
        <h4>Order details</h4>
    </div>
    <hr>
    <div class="container card-body table-responsive px-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Order status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->order_status->name }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container card-body table-responsive px-0">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Customer details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ordered by</td>
                    <td>{{ $order->first_name }} {{ $order->first_name }}</td>
                </tr>
                <tr>
                    <td>Company</td>
                    <td>{{ $order->company }}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>{{ $order->country }}</td>
                </tr>
                <tr>
                    <td>State/Region</td>
                    <td>{{ $order->region }}</td>
                </tr>
                <tr>
                    <td>Town/City</td>
                    <td>{{ $order->town }}</td>
                </tr>
                <tr>
                    <td>Street address</td>
                    <td>{{ $order->street_address }}</td>
                </tr>
                <tr>
                    <td>Postcode/ZIP</td>
                    <td>{{ $order->post_code }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $order->phone }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $order->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container card-body table-responsive px-0">
        @if ($order->remark)
            <table class="table">
                <thead>
                    <tr>
                        <th>Additional remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $order->remark }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    <div class="container card-body table-responsive px-0">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="5">Orders list</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $key => $product)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td><img src="{{ asset('storage/product/image/' . $product->image) }}" height="40"
                                alt="img"></td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <div class="text-right">
            <a href="{{ route('admin.orders.index', $client->id) }}" class="btn btn-outline-primary mr-2">Back</a>
            @if ($order->order_status_id === 1 && $order->client_id === $client->id)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-complete">Complete</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#modal-cancel">Cancel</button>
            @endif
        </div>
    </div>

    <!-- Complete order modal -->
    <div class="modal fade" id="modal-complete" tabindex="-1" aria-labelledby="modalCompleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeModalLabel">Complete product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.complete', [$client->id, $order->id]) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="container-fluild form-group">
                            <p>Are you sure you want to complete this order?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-icon">Complete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel order modal -->
    <div class="modal fade" id="modal-cancel" tabindex="-1" aria-labelledby="modalCancelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.cancel', [$client->id, $order->id]) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="container-fluild form-group">
                            <p>Are you sure you want to cancel this order?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger btn-icon">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
