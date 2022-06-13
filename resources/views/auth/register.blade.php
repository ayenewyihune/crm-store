@extends('layouts.basic')

@section('content')
    <div class="row pt-5 justify-content-center">
        <div class="col-lg-6">
            <div class="card-group d-block d-md-flex row">
                <div class="card col-md-7 p-4 mb-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <h1>Register</h1>
                            <p class="text-medium-emphasis">Create your account</p>

                            <div class="input-group my-4"><span class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-group my-4"><span class="input-group-text">
                                    <i class="fa-thin fa-at"></i></span>
                                <input id="email" type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                    value="{{ old('email') }}" placeholder="Email" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="input-group my-4"><span class="input-group-text">
                                    <i class="fa-solid fa-key"></i></span>
                                <input id="password" type="password" placeholder="Password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="input-group my-4"><span class="input-group-text">
                                    <i class="fa-solid fa-key"></i></span>
                                <input id="password-confirm" type="password" class="form-control"
                                    placeholder="Confirm password" name="password_confirmation" required
                                    autocomplete="new-password">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-block btn-primary" type="submit">Create Account</button>
                                </div>
                                <div class="col-6 text-end">
                                    <a class="btn btn-link" href="{{ route('login') }}">Already
                                        registered?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
