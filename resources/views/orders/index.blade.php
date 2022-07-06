@extends('layouts.dashboard')

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
                @yield('tbody')
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endsection
