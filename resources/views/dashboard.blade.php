@extends('layouts.dashboard')

@section('content')
    @if (!Auth::user()->store)
        <div class="alert alert-danger" role="alert">
            Your store has been deactivated. Please contact administrators.
        </div>
    @else
        <section class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 table-responsive">
                        <h5 class="p-4">Other things completed. Dashboard left unfinished due to time shortage.
                        </h5>
                        <hr>
                        <div class="card-body">
                            <canvas id="product-category" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 table-responsive">
                        <h5 class="p-4">Order chart</h5>
                        <hr>
                        <div class="card-body">
                            <canvas id="order-chart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('script')
    <script>
        var product_cat_label = [];
        var product_cat = [];
        for (let i in products) {
            for (let j in category_names) {
                if (products[i]['product_category_id'] == category_names[j]['id']) {
                    prodcut_cat_label.push(category_names[j]['name']);
                }
            }
            product_cat.push(product[i]['total']);
        }

        var productContext = document.getElementById('product-category').getContext('2d');
        var productChart = new Chart(productContext, {
            type: 'doughnut',
            data: {
                labels: label,
                datasets: [{
                    label: 'Product by category',
                    data: data,
                    backgroundColor: [
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(54, 205, 86)',
                        'rgb(255, 99, 132)'
                    ],
                    hoverOffset: 4
                }]
            }
        });
    </script>
@endsection
