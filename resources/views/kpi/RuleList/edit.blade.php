@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    label {
        font-weight: bold;
    }
</style>
@endsection
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-monitor icon-gradient bg-mean-fruit"> </i>
            </div>
            <div>Rule Management
                <div class="page-title-subheading">This is an example rule management created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            Rule
        </div>
        <div class="card-body">
            {{-- <h5 class="card-title">Rule</h5> --}}
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate action="{{route('kpi.rule-list.update',$rule->id)}}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="ruleName">Rule Name :</label>
                            <input type="text" class="form-control form-control-sm" id="ruleName"
                                placeholder="Rule Name" value="{{$rule->name}}" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ruleCategory">Rule Category :</label>
                            <select id="validationRuleCategory" class="form-control-sm form-control" name="category_id"
                                required>
                                @isset($category)
                                @foreach ($category as $item)
                                <option value="{{$item->id}}" @if ($rule->category->id === $item->id)
                                    selected
                                    @endif
                                    >
                                    {{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Rule Category.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="mesurement">Rule Type :</label>
                            <select id="validationRuleType" class="form-control-sm form-control"
                                name="kpi_rule_types_id" required>
                                <option value="">Choose...</option>
                                @isset($rulesType)
                                @foreach ($rulesType as $item)
                                <option value="{{$item->id}}" @if ($item->id === $rule->kpi_rule_types_id)
                                    selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Rule Type.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <label for="targetUnit">User actual :</label>
                            <select id="validationUserActual" class="form-control-sm form-control" name="user_actual"
                                required>
                                <option value="">Choose...</option>
                                @isset($users)
                                @foreach ($users as $item)
                                <option value="{{$item->id}}" @if ($item->id === $rule->user_actual)
                                    selected
                                    @endif>{{$item->name }} - {{$item->username}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid User Actual.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group"><label for="Calculate-type" class="">Calculate
                                    Type
                                    :</label>
                                <select id="validationCalculateType" class="form-control form-control-sm"
                                    name="calculate_type" placeholder="Calculate Type" required>
                                    <option value="">Choose...</option>
                                    @isset($calcuTypes)
                                    @foreach ($calcuTypes as $item)
                                    <option value="{{$item}}" @if ($item===$rule->calculate_type)
                                        selected
                                        @endif>
                                        {{$item}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a valid Calculate Type.
                                </div>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group"><label for="QuarterCal" class="">Quarter Calculate
                                    :</label>
                                <select id="quarter_cal" class="form-control form-control-sm" name="quarter_cal"
                                    required>
                                    <option value="">Choose...</option>
                                    @isset($quarter_cals)
                                    @foreach ($quarter_cals as $quarter_cal)
                                    <option value="{{$quarter_cal}}" @if ($quarter_cal===$rule->quarter_cal)
                                        selected
                                        @endif>
                                        {{$quarter_cal}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a valid Data Sources
                                </div>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group"><label for="DataSources" class="">DataSources
                                    :</label>
                                <select id="validationDataSources" class="form-control form-control-sm"
                                    name="department_id" required>
                                    <option value="">Choose...</option>
                                    @isset($departments)
                                    @foreach ($departments as $department)
                                    <option value="{{$department->id}}" @if ($department->id===$rule->department_id)
                                        selected
                                        @endif>
                                        {{$department->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a valid Data Sources
                                </div>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="BaseLine">Base Line :</label>
                            <input type="number" class="form-control form-control-sm" id="base_line"
                                value="{{number_format($rule->base_line,2)}}" name="base_line" min="0.00" step="0.01"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label for="Max">Max :</label>
                            <input type="number" class="form-control form-control-sm" id="max"
                                value="{{number_format($rule->max,2)}}" name="max" min="0.00" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="Rule" class="">Rule KPI
                                    :</label>
                                <select id="validationRelationRule" class="form-control form-control-sm" name="parent">
                                    <option value="">Choose...</option>
                                    @isset($rules)
                                    @foreach ($rules as $item)
                                    <option value="{{$item->id}}" @if ($rule->parent_to->id === $item->id)
                                        selected
                                        @endif>
                                        {{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a valid Rule
                                </div>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="description">Detinition :</label>
                            <textarea class="form-control form-control-sm" id="description" name="description"
                                rows="3">{{$rule->description}}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="CalculationMachianism">Calculation Machianism :</label>
                            <textarea class="form-control form-control-sm" id="CalculationMachianism" name="desc_m"
                                rows="3">{{$rule->desc_m}}</textarea>
                        </div>
                    </div>
                    {{-- <div class="form-row">
                        <div class="col-md-4 mb-3 mt-2">

                        </div>
                    </div> --}}
            </div>
        </div>
        <div class="card-footer"><button class="mb-2 mr-2 mt-2 btn btn-success">Save</button></div>
        </form>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\rule\create.js')}}" defer></script>
@endsection