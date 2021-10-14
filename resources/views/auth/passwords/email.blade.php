@extends('layouts.blank')

@section('content_blank')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset password') }} (ใส่อีเมล์ เพื่อตั้งรหัสผ่าน)</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right"><strong>{{ __('Email')
                                    }}</strong></label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control-sm form-control @error('email') is-invalid @enderror"
                                    placeholder="กรอก อีเมล์ตรงนี้" name="email" value="{{ old('email') }}" required
                                    autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">

                            <div class="col-md-6 ">
                                <a class="btn btn-danger text-md-right" href="{{ url()->previous() }}">
                                    {{ __('Back') }} (กลับ)
                                </a>
                            </div>
                            <div class="col-md-6 ">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send password reset link') }} (คลิกเพื่อสร้างรหัสใหม่)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
