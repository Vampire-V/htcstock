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
            <div>Self Evaluate
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
                <h5 class="card-title">{{$period->name}} {{$period->year}}</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>Status <span
                                class="{{Helper::kpiStatusBadge($evaluate->status)}}">{{$evaluate->status}}</span></h5>
                    </div>
                </div>
            </div>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$user->name }}" disabled>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please choose a Staff Name.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{$user->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" disabled>
                                <div class="invalid-feedback">
                                    Please choose a Department.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{$user->positions->name}}" disabled>
                            <div class="invalid-feedback">
                                Please provide a valid Position.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="template">Template</label>
                            <select id="validationTemplate" class="form-control-sm form-control" name="template_id"
                                required onchange="changeTemplate(this)">
                                <option value="">Choose...</option>
                                @isset($templates)
                                @foreach ($templates as $item)
                                <option value="{{$item->id}}" @if ($evaluate->template_id === $item->id)
                                    selected
                                    @endif>
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
                    </div>
                    <div class="form-row">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@isset($category)
<div id="all-table">
    @foreach ($category as $group)
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="card-header">
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            <h5 class="card-title">{{$group->name}}</h5>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right">
                        <div role="group" class="btn-group-sm btn-group">
                            @if ($group->name === 'key-task')
                            <button class="mb-2 mr-2 btn btn-danger" id="rule-remove-modal"
                                onclick="deleteRuleTemp(this)" disabled>Delete Selected
                                Rule</button>
                            <button class="mb-2 mr-2 btn btn-primary" data-group="{{$group}}" data-toggle="modal"
                                data-target="#rule-modal" id="rule-add-modal" disabled>Add
                                New Rule</button>
                            @endif
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
                                <th>Base Line %</th>
                                <th>Max %</th>
                                <th>Weight %</th>
                                <th>Amount</th>
                                <th>Target %</th>
                                @if ($group->name === 'key-task')
                                <th>#</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">Total Weight :</td>
                                <td>0</td>
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

{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" id="submit" onclick="submit()" disabled>Save</button>
            <button class="mb-2 mr-2 btn btn-success" id="submit-to-user" onclick="submitToUser()" disabled>Submit to
                staff</button>
            {{-- <button class="mb-2 mr-2 btn btn-danger">Delete</button> --}}
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
                <h5 class="modal-title" id="rule-modal-label">New Rule to : Key-Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-rule">
                    <input type="hidden" name="parent_rule_template_id" value="">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="rule-name" class="">Rule Name
                                    :</label>
                                <select id="rule-name" class="form-control form-control-sm" name="rule_id_add">
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addKeyTask(this)">Add</button>
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
    const  staff = {!!json_encode($user)!!}, period = {!!json_encode($period)!!}, evaluate = {!!json_encode($evaluate)!!}
</script>
<script src="{{asset('assets\js\kpi\evaluationForm\edit.js')}}" defer>
    // new form object evaluateForm
</script>

<script>

</script>
@endsection