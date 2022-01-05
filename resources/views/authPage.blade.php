@extends('default')
@section('link')
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="text-center mt-5">
            <div class="d-flex flex-column bd-highlight mb-3">
                <h2 class="font-weight-bold py-3">{{ $title }}</h2>
            </div>
        </div>
        @if ($title === 'Sign in')
        <div class="text-center my-2">
            <a class="auth-button" href="{{ $authUrl }}">
                Sign in with Hydra
            </a>
        </div>
        @endif
        <div class="text-center my-2">
            <a class="auth-button" href="{{ $lineLoginUrl }}">
                <div>
                    <img width="25" height="25" src="{{ asset('/images/btn_base.png') }}">
                        @if ($title === 'Sign up')
                        Sign up with Line
                        @else
                        Sign in with Line
                        @endif
                </div>
            </a>
        </div>
        <hr class="my-3">
        <div class="text-center">
            <a href="{{ $homeUrl }}">Home</a>
        </div>
    </div>
</div>
@endsection
