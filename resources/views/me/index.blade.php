@extends('layouts.app')

@section('sidebar')
@include('includes.sidebar.profile')
@stop
@section('style')
<link href="{{ asset('assets\filepond-master\dist\filepond.css') }}" rel="stylesheet" />
<link href="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-preview.min.css') }}" rel="stylesheet" />
<style>
    label>span {
        color: red
    }

    /*
 * FilePond Custom Styles
 */
    .filepond--drop-label {
        color: #4c4e53;
    }

    .filepond--label-action {
        text-decoration-color: #babdc0;
    }

    .filepond--panel-root {
        background-color: #edf0f4;
    }


    /**
 * Page Styles
 */
    /* html {
        padding: 20vh 0 0;
    } */

    .filepond--root {
        width: 170px;
        margin: 0 auto;
    }
</style>
@endsection
@section('content')
{{-- <div class="app-page-title">
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
</div> --}}
<div class="col-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            {{-- <h5 class="card-title">Profile</h5> --}}
            <form class="" action="{{route('me.user.update',$user->id)}}" method="post" id="form-profile">
                @csrf
                @method('PUT')
                <input type="file" class="filepond" id="filepond" name="filepond"
                    accept="image/png, image/jpeg, image/gif" />
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Username" class="">{{ __('profile.username') }} </label>
                            <input name="username" id="username" placeholder="Username placeholder" type="text"
                                class="form-control form-control-sm"
                                value="{{ $user->username }}" disabled >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="name" class="">{{ __('profile.name') }} (TH) <span>*</span></label>
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
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="name" class="">{{ __('profile.name') }} (EN) <span>*</span></label>
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
                            <label for="email" class="">{{ __('profile.email') }}</label>
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
                            <label for="Phone" class="">{{ __('profile.phone') }}</label>
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
                            <label for="Manager" class="">{{ __('profile.manager') }}</label>
                            <select name="head_id" id="head_id" class="form-control form-control-sm" >
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
                            <label for="Division" class="">{{ __('profile.division') }} </label>
                            <select name="division" id="division" class="form-control form-control-sm" >
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
                            <label for="Department" class="">{{ __('profile.department') }} </label>
                            <select name="department" id="department" class="form-control form-control-sm" >
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
                            <label for="Position" class="">{{ __('profile.position') }}</label>
                            <select name="position" id="position" class="form-control form-control-sm" >
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
                <div class="d-flex justify-content-center">
                    <button class="btn btn-danger mr-2" onclick="window.history.back()" type="button">Back</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('second-script')
<script src="{{ asset('assets\filepond-master\dist\filepond.js') }}" defer></script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-file-encode.min.js') }}" defer></script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-file-validate-type.min.js') }}" defer>
</script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-exif-orientation.min.js') }}" defer>
</script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-preview.min.js') }}" defer></script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-crop.min.js') }}" defer></script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-resize.min.js') }}" defer></script>
<script src="{{ asset('assets\filepond-master\dist\plugin\filepond-plugin-image-transform.min.js') }}" defer></script>
<script>
    var image = {!!json_encode(asset("/storage" . $user->image))!!}
</script>
<script src="{{asset('assets\js\profile\me.js')}}" defer></script>
@endsection