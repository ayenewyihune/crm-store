@extends('orders.index')

@section('tbody')
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
            <td><a href="{{ route('orders.show_my_order', $order->id) }}" class="btn btn-primary">View</a></td>
        </tr>
    @endforeach
@endsection
