@extends('layouts.dashboard_admin')

@section('content')
    @if (!Auth::user()->store)
        <div class="alert alert-danger" role="alert">
            Your store has been deactivated. Please contact administrators.
        </div>
    @else
        <section class="container py-4">
            <div class="card p-3 table-responsive">
                <h4 class="card-title mt-2 mb-0 ml-2">Sample chart (Order trend line)</h4>
                <hr>
                <div class="card-body my-5">
                    <canvas id="order-trend"></canvas>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('script')
    <script>
        const order_trend = {!! json_encode($order_trend, JSON_HEX_TAG) !!};

        var orderTrendContext;
        var orderTrendChart;

        orderTrendContext = document.getElementById('order-trend').getContext('2d');
        orderTrendChart = new Chart(orderTrendContext, {
            type: 'line',
            data: {
                datasets: [{
                    data: order_trend,
                    label: "Orders",
                    backgroundColor: '#1e90ff',
                    borderColor: '#1e90ff',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Orders'
                        }
                    },
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    }
                }
            }
        });
    </script>
@endsection
