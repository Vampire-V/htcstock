@extends('layouts.app')

@section('sidebar')
@include('includes.sidebar.profile')
@stop

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
                </i>
            </div>
            <div>Profile
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Profile</h5>
            <form class="" action="{{route('me.user.update',$user->id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name" class="">{{ __('Name') }} (TH)</label>
                            <input name="name:th" id="name_th" placeholder="Name placeholder" type="text"
                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                value="{{ $user->translate('th')->name }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name" class="">{{ __('Name') }} (EN)</label>
                            <input name="name:en" id="name_en" placeholder="Name placeholder" type="text"
                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                value="{{ $user->translate('en')->name }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="email" class="">{{ __('E-Mail Address') }}</label>
                            <input name="email" id="email" placeholder="with a placeholder" type="email"
                                value="{{ $user->email }}" required autocomplete="email" readonly
                                class="form-control form-control-sm">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Phone" class="">{{ __('Phone') }}</label>
                            <input name="phone" id="phone" placeholder="Phone placeholder" type="text"
                                class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                value="{{ $user->phone }}" required autocomplete="phone" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Division" class="">{{ __('Division') }}</label>
                            {{-- <input name="division" id="division" placeholder="with a placeholder" type="division"
                                    value="{{ $user->divisions->name }}" required autocomplete="division" readonly
                            class="form-control form-control-sm"> --}}
                            <select name="division" id="division" class="form-control form-control-sm">
                                <option value="">Choose...</option>
                                @isset($divisions)
                                @foreach ($divisions as $division)
                                <option value="{{$division->id}}" @if ($division->id === $user->divisions_id)
                                    selected
                                    @endif>{{$division->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('division')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Department" class="">{{ __('Department') }}</label>
                            {{-- <input name="department" id="department" placeholder="Department placeholder"
                                    type="text"
                                    class="form-control form-control-sm @error('department') is-invalid @enderror"
                                    value="{{ $user->department->name }}" required autocomplete="department" readonly
                            autofocus> --}}
                            <select name="department" id="department" class="form-control form-control-sm">
                                <option value="">Choose...</option>
                                @isset($departments)
                                @foreach ($departments as $department)
                                <option value="{{$department->id}}" @if ($department->id === $user->department_id)
                                    selected
                                    @endif>{{$department->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('department')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Position" class="">{{ __('Position') }}</label>
                            {{-- <input name="position" id="position" placeholder="with a placeholder" type="position"
                                    value="{{ $user->positions->name }}" required autocomplete="position" readonly
                            class="form-control form-control-sm"> --}}
                            <select name="position" id="position" class="form-control form-control-sm">
                                <option value="">Choose...</option>
                                @isset($positions)
                                @foreach ($positions as $position)
                                <option value="{{$position->id}}" @if ($position->id === $user->positions_id)
                                    selected
                                    @endif>{{$position->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="mt-2 btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
</div>
@stop