@extends('layouts.dashboard')

@section('content')
    @if (!Auth::user()->store)
        <div class="alert alert-danger" role="alert">
            Your store has been deactivated. Please contact administrators.
        </div>
    @endif
@endsection
