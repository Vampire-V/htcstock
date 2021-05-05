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
                                value="{{ $user->translate('th') ? $user->translate('th')->name : null }}" required
                                autocomplete="name" autofocus>

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
                                value="{{ $user->translate('en') ? $user->translate('en')->name : null }}" required
                                autocomplete="name" autofocus>

                            @error('name')
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Manager" class="">{{ __('Manager') }}</label>
                            <select name="head_id" id="head_id" class="form-control form-control-sm">
                                <option value="">Choose...</option>
                                @isset($users)
                                @foreach ($users as $item)
                                <option value="{{$item->username}}" @if ($item->username === strval($user->head_id))
                                    selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('head_id')
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

@stop

@section('second-script')
<script>
    (function () {
        'use strict';

        document.addEventListener('DOMContentLoaded', function () {
            // Supporting Documents
            $("#head_id").select2({
                placeholder: 'Select Manager',
                allowClear: true
            });
            $("#division").select2({
                placeholder: 'Select Division',
                allowClear: true
            });
            $("#department").select2({
                placeholder: 'Select Department',
                allowClear: true
            });
            $("#position").select2({
                placeholder: 'Select Position',
                allowClear: true
            });
        })

        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            let forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            validationForm(forms)

        }, false);

    })();
</script>
@endsection