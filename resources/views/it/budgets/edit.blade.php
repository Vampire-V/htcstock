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
    <div class="col-lg-6">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="needs-validation" novalidate action="{{route('it.check.budgets.update',$budget->id)}}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-9 mb-3">
                            <label for="validationBudgetsOfMonth">{{ __('itstock.budget.budget-month') }}</label>
                            {{-- <input type="number" name="test"> --}}
                            <input type="number" class="form-control-sm form-control" id="budgets_of_month" name="budgets_of_month"
                                min="1" step="0.01" value="{{$budget->budgets_of_month}}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn-shadow btn btn-primary" type="submit" style="margin-top: 30px">
                                {{ __('itstock.budget.submit-form') }}</button>
                        </div>
                    </div>
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
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">{{date("F", mktime(0, 0, 0, $budget->month, 1))}} {{$budget->year}}</h5>
                <table class="mb-0 table table-hover" id="table-budgets-transaction">
                    <thead>
                        <tr>
                            <th>{{ __('itstock.budget.a-date') }}</th>
                            <th>{{ __('itstock.budget.ir-no') }}</th>
                            <th>{{ __('itstock.budget.invoice') }}</th>
                            <th>{{ __('itstock.budget.equipment') }}</th>
                            <th>{{ __('itstock.budget.unit') }}</th>
                            <th>{{ __('itstock.budget.quantity') }}</th>
                            <th>{{ __('itstock.budget.unit-cost') }}</th>
                            <th>{{ __('itstock.budget.amount') }}</th>
                            <th>{{ __('itstock.budget.accumulate-amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $item)
                        <tr>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->ir_no}}</td>
                            <td>{{$item->invoice_no}}</td>
                            <td>{{$item->accessorie->access_name}}</td>
                            <td>{{$item->accessorie->unit}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{Helper::convertToTHB($item->unit_cost)}}</td>
                            <td>{{Helper::convertToTHB($item->amount)}}</td>
                            <td>{{Helper::convertToTHB($item->amt)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <td>{{Helper::convertToTHB($amountTotal)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                            <td style="text-align: left">{{ __('itstock.budget.remain-budget') }}</td>
                            <td>{{Helper::convertToTHB($remainBudget)}}</td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                            <td style="text-align: left">{{ __('itstock.budget.grand-total-budget') }}</td>
                            <td>{{Helper::convertToTHB($budget->budgets_of_month)}}</td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                            <td style="text-align: left">{{ __('itstock.budget.total-used-budge') }}</td>
                            <td>{{Helper::convertToTHB($amountTotal)}}</td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                            <td style="text-align: left">{{ __('itstock.budget.total-remain-budget') }}</td>
                            <td>{{Helper::convertToTHB($remainBudget)}}</td>
                        </tr>
                    </tbody>
                </table>
                {{-- <button type="submit" class="btn btn-primary">Submit form</button> --}}
            </div>
        </div>
    </div>
</div>
@stop