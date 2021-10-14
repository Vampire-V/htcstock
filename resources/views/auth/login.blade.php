@extends('layouts.blank')

@section('content_blank')
<form class="form-signin" method="POST" action="{{ route('login') }}">
    @csrf
    <img class="mb-4" src="{{asset('assets/images/newhaier.png')}}" alt="LOGO">
    {{-- <h1 class="h3 mb-3 font-weight-normal">{{ __('Login') }}</h1> --}}
    <label for="inputEmail" class="sr-only">{{ __('Email or username') }}</label>
    <input id="username" type="username" class="form-control-sm form-control @error('username') is-invalid @enderror" name="username"
        value="{{ old('username') }}" placeholder="{{ __('Email or Employee ID') }} (อีเมล์ หรือ รหัสพนักงาน)" required autocomplete="username"
        autofocus>
    @error('username')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    <label for="inputPassword" class="sr-only">{{ __('Password') }}</label>
    <input id="password" type="password" class="form-control-sm form-control @error('password') is-invalid @enderror" name="password"
        placeholder="{{ __('Password') }} (รหัสผ่าน)" required autocomplete="current-password">

    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    {{-- <div class="checkbox mb-3">
        <label>
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            {{ __('remember') }}
        </label>
    </div> --}}
    <button class="btn btn-lg btn-primary btn-block" type="submit">{{ __('Login') }}</button>
    @if (Route::has('password.request'))
    <a class="btn btn-link" href="{{ route('password.request') }}">
        {{ __('Forgot your password?') }} <span style="color: red;">(ไม่รู้รหัสผ่านคลิกที่นี้)</span>
    </a>
    @endif
    {{-- <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p> --}}
</form>
@endsection
