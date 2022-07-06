@extends('layouts.store')

@section('content')
    <div class="py-5 p-md-5">
        <div class="text-center">
            <h1 class="text-black">Cart</h1>
        </div>
    </div>
    <form action="{{ route('store.cart.update', $user->id) }}" method="POST">
        @csrf
        @method('put')

        <div class="card table-responsive my-3">
            @include('inc.messages')
            <table class="table">
                <thead class="bg-light">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td><a class="text-danger" href="{{ route('store.cart.delete', [$user->id, $cart->id]) }}"><i
                                        class="fa-regular fa-xl fa-circle-xmark"></i></a></td>
                            <td><img src="{{ asset('storage/product/image/' . $cart->product->image) }}" alt=""
                                    style="width: 45px; height: 45px" /></td>
                            <td>{{ $cart->product->name }}</td>
                            <td>$ {{ $cart->product->price }}</td>
                            <td><input type="number" name="quantity[{{ $cart->id }}]" id="quantity" step="1"
                                    min="1" value="{{ $cart->quantity }}" style="width:70px" required></td>
                            <td>$ {{ $cart->quantity * $cart->product->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" id="submit" class="btn btn-primary btn-lg" disabled>Update Cart</button>
        </div>
    </form>

    <div class="row py-3">
        <div class="col-md-6 offset-md-6">
            <table class="table">
                <thead class="bg-light">
                    <tr>
                        <th colspan="2">
                            <h5 class="my-0">Cart totals</h5>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td>$ {{ $total_before_tax }}</td>
                    </tr>
                    <tr>
                        <th>Tax</th>
                        <td>$ {{ $total_before_tax * 0.15 }}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>$ {{ $total_before_tax * 1.15 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a class="btn btn-primary btn-lg card" href="{{ route('store.checkout', $user->id) }}"
                                role=" button">Proceed
                                to Checkout</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('input[name^="quantity"]').change(function() {
                $('#submit').removeAttr('disabled');
            });
        });
    </script>
@endsection
