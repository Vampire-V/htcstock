@extends('layouts.blank')

@section('content_blank')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ยืนยันที่อยู่อีเมลของคุณ') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('ลิงก์ยืนยันใหม่ถูกส่งไปยังที่อยู่อีเมลของคุณ') }}
                        </div>
                    @endif

                    {{ __('ก่อนดำเนินการต่อโปรดตรวจสอบลิงก์การยืนยันของคุณทางอีเมล') }}
                    {{ __('หากคุณไม่ได้รับอีเมล') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('คลิกที่นี่เพื่อขออีก') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
