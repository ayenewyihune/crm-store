@extends('layouts.dashboard_admin')

@section('content')
    <h4>Orders</h4>
    <hr>
    <div class="card-body table-responsive px-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ordered by</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>Town</th>
                    <th>Total products</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order)
                    <tr>
                        <th scope="row">
                            {{ ($orders->currentpage() - 1) * $orders->perpage() + $key + 1 }}
                        </th>
                        <td>{{ $order->first_name }} {{ $order->first_name }}</td>
                        <td>{{ $order->country }}</td>
                        <td>{{ $order->region }}</td>
                        <td>{{ $order->town }}</td>
                        <td>{{ $order->products()->count() }}</td>
                        <td>{{ $order->order_status->name }}</td>
                        <td><a href="{{ route('admin.orders.show', [$client->id, $order->id]) }}"
                                class="btn btn-primary">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endsection
