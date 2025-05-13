@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('assets/media/logos/logo.png') }}" class="img-fluid mb-5 tw-h-16" alt="Logo"/>
                </div>
                <div class="card bg-secondary-subtle border border-secondary">
                    <div class="card-body">
                        <h4 class="tw-text-3xl fw-light">{{ __('Login') }}</h4>
                        <p>
                            {{ __('Please enter your email and password to login') }}
                        </p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row my-10">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>

                                <div>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row my-10">
                                <label for="password" class="form-label">{{ __('Password') }}</label>

                                <div>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="my-10">
                                <div class="form-check">
                                    <input class="form-check-input rounded-0" type="checkbox" name="remember"
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary my-4 tw-px-10">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="d-block" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
