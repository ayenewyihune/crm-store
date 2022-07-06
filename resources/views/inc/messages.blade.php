@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@endif

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

@if (session('product-add'))
    <div class="alert alert-success" role="alert">
        <div class="row">
            <div class="col-8">
                {{ session('product-add') }}
            </div>
            <div class="col-4 ">
                <a href="{{ route('store.cart', $user->id) }}"
                    class="btn d-none d-md-block btn-primary float-end">View
                    cart</a>
                <a href="{{ route('store.cart', $user->id) }}" class="btn btn-primary d-md-none float-end">Cart</a>
            </div>
        </div>
    </div>
@endif

@if (session('product-add-error'))
    <div class="alert alert-danger" role="alert">
        <div class="row">
            <div class="col-8">
                {{ session('product-add-error') }}
            </div>
            <div class="col-4 ">
                <a href="{{ route('store.cart', $user->id) }}"
                    class="btn d-none d-md-block btn-primary float-end">View
                    cart</a>
                <a href="{{ route('store.cart', $user->id) }}" class="btn btn-primary d-md-none float-end">Cart</a>
            </div>
        </div>
    </div>
@endif
