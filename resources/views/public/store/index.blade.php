@extends('layouts.store')

@section('content')
    <div class="pt-3 p-md-5">
        <div class="text-center">
            <h3 class="text-black">Recently Added</h3>
            <div class="row">
                <div class="col-2 offset-5">
                    <hr class="bg-dark mx-4" style="height: 2px">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-3 p-1">
                <a href="{{ route('store.products.show', [$user->id, $product->id]) }}"
                    style="text-decoration: none; color:black;">
                    <div class="card">
                        <img height="250px" width="100%" src="{{ asset('storage/product/image/' . $product->image) }}"
                            alt="">
                        <div class="px-3 mb-2">
                            <h5 class="mb-0" style="font-family: 'Open Sans'">{{ $product->name }}</h5>
                            <small class="fst-italic fw-light" style="color: darkgray">
                                {{ implode(', ',$product->product_categories()->pluck('name')->toArray()) }}</small>
                            <p>$ {{ $product->price }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

{{-- @section('script')
    <script>
        $(document).ready(function() {
            fetch_products();

            function fetch_products(query = '') {

                $.ajax({
                    url: "{{ route('member_search') }}",
                    method: 'GET',
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#membersList").html(data.table_data);
                        $("#total_records").text(data.total_data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $(this).val();
                fetch_member_data(query);
            });
        });
    </script>
@endsection --}}