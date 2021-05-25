@extends('layouts.app')
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    .select2-hidden-accessible {
        position: inherit !important;
    }

    .select2,
    .select2-containe {
        display: inherit !important;
    }
</style>
@endsection
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
            <div>Evaluate Form
                <div class="page-title-subheading">This is an example self evaluate created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
        </div>
    </div>
</div>
{{-- Display user detail --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="card-header">
                <h5 class="card-title"></h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>Status <span class="badge badge-info"></span></h5>
                    </div>
                </div>
            </div>
            <div class="position-relative form-group">
                <form class="needs-validation" id="create-evaluate" novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{auth()->user()->name }}" disabled>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please choose a Staff Name.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{auth()->user()->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" disabled>
                                <div class="invalid-feedback">
                                    Please choose a Department.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{auth()->user()->positions->name}}" disabled>
                            <div class="invalid-feedback">
                                Please provide a valid Position.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="template">Template</label>
                            <select id="template" class="form-control-sm form-control" name="template_id" required
                                onchange="changeTemplate(this)" data-toggle="collapse">
                                <option value="">Choose...</option>
                                @isset($templates)
                                @foreach ($templates as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Template.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="Year">Period Name</label>
                            <select name="period" id="period" class="form-control-sm form-control" required>
                                <option value="">Choose...</option>
                                @isset($months)
                                @foreach ($months as $month)
                                <option value="{{date('m', strtotime($month->name." 1 2021"))}}">{{$month->name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Period.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="Year">Year</label>
                            <select name="year" id="year" class="form-control-sm form-control" required>
                                <option value=""></option>
                                @isset($years)
                                @foreach ($years as $year)
                                <option value="{{$year->year}}">{{$year->year}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Year.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="collapse" id="collapseExample123">
    @isset($category)
    <div id="group-table">
        @foreach ($category as $group)
        <div class="col-lg-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <label for="department" class="mb-2 mr-2">{{$group->name}} (Weight) :</label>
                        <div class="btn-actions-pane">
                            <div role="group" class="btn-group-sm btn-group">
                                <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="0.01"
                                    id="weight-{{$group->name}}" name="weight_{{str_replace("-","_",$group->name)}}"
                                    readonly> %
                            </div>
                        </div>
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">
                                <button class="mb-2 mr-2 btn btn-danger" id="rule-remove-modal"
                                    onclick="removeInSelected(this)">Delete Selected
                                    Rule</button>
                                {{-- @if ($group->name === "kpi") --}}
                                <button class="mb-2 mr-2 btn btn-primary" data-group="{{$group}}" data-toggle="modal"
                                    data-target="#rule-modal" id="rule-add-modal">Add
                                    New Rule</button>
                                {{-- @endif --}}

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="mb-0 table table-sm" id="table-{{$group->name}}">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Rule Name</th>
                                    <th>Description</th>
                                    <th>Base Line</th>
                                    <th>Max</th>
                                    <th>Weight %</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>%Ach</th>
                                    <th>%Cal</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row" rowspan="4"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;">Total Weight :</td>
                                    <td>0</td>
                                    <td rowspan="5"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endisset
</div>

{{-- Calculation Summary --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Calculation Summary</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm" id="table-calculation">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Weight</th>
                            <th>%Cal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($category)
                        @foreach ($category as $item)
                        <tr>
                            <th>{{$item->name}}</th>
                            <td>0.00%</td>
                            <td>0.00%</td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Total</th>
                            <td>0.00%</td>
                            <td>0.00%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" id="submit" onclick="submit()">Save</button>
            <button class="mb-2 mr-2 btn btn-success" id="submit-to-user" onclick="submitToManager()">Save & Send to
                manager</button>
        </div>
    </div>
</div>

@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="rule-modal" tabindex="-1" role="dialog" aria-labelledby="rule-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rule-modal-label">New Rule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-rule">
                    <input type="hidden" name="parent_rule_template_id" value="">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="rule_name" class="">Rule Name
                                    :</label>
                                <select id="rule_name" class="form-control form-control-sm" name="rule_name">
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addRule(this)">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer>
    // all method 
</script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer>
    // all method KPI 
</script>
<script defer>
    // variable
</script>
<script src="{{asset('assets\js\kpi\evaluationSelf\create.js')}}" defer>
    // new form object evaluateForm 
</script>

@endsection