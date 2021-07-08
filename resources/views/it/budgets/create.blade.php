@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.it');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
                </i>
            </div>
            <div>{{ __('itstock.budget.budget-manage') }}
                {{-- <div class="page-title-subheading">Tables are the backbone of almost all web
                        applications.
                    </div> --}}
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block">
                {{-- <button type="button" data-toggle="modal" data-target="#accessoriesModal"
                    class="btn-shadow  btn btn-info" data-param="">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    เพิ่ม
                </button> --}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">{{ __('itstock.budget.budget-create-form') }}</h5>
                <form class="needs-validation" novalidate action="{{route('it.check.budgets.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="budgets_of_month"
                            class="col-md-3 col-form-label text-md-right">{{ __('itstock.budget.budgets-of-month') }}</label>
                        <div class="col-md-4">
                            <input id="budgets_of_month" type="number"
                                class="form-control-sm form-control @error('budgets_of_month') is-invalid @enderror"
                                name="budgets_of_month" value="" min="1" required autocomplete="budgets_of_month" step="0.01"
                                autofocus>

                            @error('budgets_of_month')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="invalid-feedback">
                                Please provide a valid budgets of month.
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="month" class="col-md-3 col-form-label text-md-right">{{ __('itstock.budget.month') }}</label>
                        <div class="col-md-4">
                            <select class="form-control-sm form-control" name="month" id="month" required>
                                <option value="">--เลือก--</option>
                                @foreach ($months as $key => $item)
                                <option value="{{$key}}">{{$item}}
                                </option>
                                @endforeach
                            </select>

                            @error('month')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="year" class="col-md-3 col-form-label text-md-right">{{ __('itstock.budget.year') }}</label>
                        <div class="col-md-4">
                            <select class="form-control-sm form-control" name="year" id="year" required>
                                <option value="">--เลือก--</option>
                                @foreach (range( date('Y'), $earliest_year ) as $i)
                                <option value="{{$i}}">{{$i}}</option>
                                @endforeach
                            </select>

                            @error('year')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('itstock.budget.submit-form') }}</button>
                </form>
                <script>
                    (function () {
                    'use strict';
                    window.addEventListener('load', function () {
                        var forms = document.getElementsByClassName('needs-validation');
                        validationForm(forms)
                    }, false);
                })();
                </script>
            </div>
        </div>
    </div>
</div>
@stop