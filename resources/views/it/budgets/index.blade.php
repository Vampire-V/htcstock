@extends('layouts.app')
@section('sidebar')

@section('style')
<style>
    label {
        font-weight: bold;
    }
</style>
    
@endsection
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
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
            <div class="d-inline-block">
                <a href="{{route('it.check.budgets.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    {{ __('itstock.budget.add') }}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                {{ __('Form search') }}
            </div>
            <div class="card-body">
                <form action="#" method="GET">
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="month">{{ __('itstock.budget.month') }}</label>
                            <select class="form-control-sm form-control" name="month" id="month">
                                <option value="">--เลือก--</option>
                                @foreach ($months as $key => $item)
                                <option value="{{$key}}" {{$selectedMonth == $key ? 'selected' : ''}}>{{$item}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year">{{ __('itstock.budget.year') }}</label>
                            <select class="form-control-sm form-control" name="year" id="year">
                                <option value="">--เลือก--</option>
                                @foreach (range( date('Y'), $earliest_year ) as $i)
                                <option value="{{$i}}" {{$selectedYear == $i ? 'selected' : ''}}>{{$i}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn-shadow btn btn-info" type="submit" style="margin-top: 30px">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-business-time fa-w-20"></i>
                                </span>
                                {{ __('itstock.budget.search') }}</button>
                        </div>
                    </div>
                </form>
                <script>
                    (function () {
                    'use strict';
                    window.addEventListener('load', function () {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        
                    }, false);
                })();
                </script>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                {{ __('itstock.budget.budget-manage') }}
            </div>
            <div class="card-body">
                {{-- <h5 class="card-title">{{ __('itstock.budget.budget-manage') }}</h5> --}}
                <table class="mb-0 table table-hover" id="table-budgets">
                    <thead>
                        <tr>
                            <th width="150px">#</th>
                            <th>{{ __('itstock.budget.budget') }}</th>
                            <th>{{ __('itstock.budget.month') }}</th>
                            <th>{{ __('itstock.budget.year') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($budgets)
                        @foreach ($budgets as $key => $budget)
                        <tr>
                            <td><a href="{{route('it.check.budgets.edit',$budget->id)}}"><button type="button"
                                        class="btn btn-primary btn-sm float-left">{{ __('itstock.budget.detail') }}</button></a>
                            </td>
                            <td>{{Helper::convertToTHB($budget->budgets_of_month)}}
                            </td>
                            <td>{{ date("F", mktime(0, 0, 0, $budget->month, 1)) }}</td>
                            <td>{{$budget->year}}</td>
                        </tr>
                        @endforeach
                        @endisset

                    </tbody>
                </table>
            </div>
            @isset($budgets)
            {{ $budgets->appends($query)->links() }}
            @endisset
        </div>
    </div>
</div>
@stop