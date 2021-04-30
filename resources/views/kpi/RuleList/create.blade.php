@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
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
        <div class="card-body">
            <h5 class="card-title">Rule Management</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate action="{{route('kpi.rule-list.store')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ruleCategory">Rule Category :</label>
                            <select id="validationRuleCategory" class="form-control-sm form-control" name="category_id"
                                required>
                                <option value="">Choose...</option>
                                @isset($category)
                                @forelse ($category as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @empty
                                <p>No Rule Category</p>
                                @endforelse
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Rule Category.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ruleName">Rule Name :</label>
                            <input type="text" class="form-control form-control-sm" id="ruleName" name="name"
                                placeholder="Rule Name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="description">Description :</label>
                            <input type="text" class="form-control form-control-sm" id="description"
                                placeholder="Description" name="description">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="mesurement">Rule Type :</label>
                            <select id="validationRuleType" class="form-control-sm form-control"
                                name="kpi_rule_types_id" required>
                                <option value="">Choose...</option>
                                @isset($rulesType)
                                @foreach ($rulesType as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
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
                        <div class="col-md-4 mb-3">
                            <label for="targetUnit">User actual :</label>
                            <select id="validationUserActual" class="form-control-sm form-control"
                                name="user_actual" required>
                                <option value="">Choose...</option>
                                @isset($users)
                                @foreach ($users as $item)
                                <option value="{{$item->id}}">{{$item->name }}</option>
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
                        <div class="col-md-4 mb-3">
                            <div class="position-relative form-group"><label for="Calculate-type" class="">Calculate
                                    Type
                                    :</label>
                                <select id="validationCalculateType" class="form-control form-control-sm"
                                    name="calculate_type" placeholder="Calculate Type" required>
                                    <option value="">Choose...</option>
                                    @isset($calcuTypes)
                                    @foreach ($calcuTypes as $item)
                                    <option value="{{$item}}">{{$item}}</option>
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
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <button class="mb-2 mr-2 btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\rule\create.js')}}" defer></script>
@endsection