@extends('layouts.app')

@section('sidebar')
@include('includes.sidebar.legal');
@stop

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
                </i>
            </div>
            <div>User Account
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">Profile</h5>
                <form action="{{route('me.user.update',$user->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="name" class="col-md-1 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <label for="email"
                            class="col-md-1 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $user->email }}" required autocomplete="email" readonly>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-md-1 col-form-label text-md-right">{{ __('Phone') }}</label>

                        <div class="col-md-3">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                name="phone" value="{{ $user->phone }}" required autocomplete="phone">

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="roles" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
                    <div class="col-md-6">
                        <div class="row">
                            @foreach ($roles as $role)
                            <div class="form-check col-md-4" style="margin-top: 10px;">
                                <input type="checkbox" name="roles[]" id="" value="{{$role->id}}"
                                    @if($user->roles->pluck('id')->contains($role->id)) checked @endif>
                                <label>{{$role->name}}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('roles')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
            </div> --}}

            <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
</div>
@stop