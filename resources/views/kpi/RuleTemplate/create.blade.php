@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    /* select2 UI */
    .select2-hidden-accessible {
        position: inherit !important;
    }

    .select2,
    .select2-containe {
        display: inherit !important;
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
            <div>Rule Template Detail Management
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
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="ruleTemplateName">Rule template name :</label>
                        <input type="text" class="form-control form-control-sm" id="validationTemplate" name="name"
                            value="{{$template->name}}" placeholder="Rule template name" readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Rule template.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="department">Department :</label>
                        <input type="text" class="form-control form-control-sm" id="validationDepartment"
                            name="department" value="{{$template->department->name}}" placeholder="Rule template name"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Department.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        {{-- <button class="mb-2 mr-2 btn btn-primary mt-4" type="submit">Save</button> --}}
                    </div>
                </div>

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
                <h5 class="card-title">{{$group->name}}</h5>
                <div class="card-header">
                    <label for="department" class="mb-2 mr-2">Weight :</label>
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0.00" step="0.01"
                                max="100" id="weight-{{$group->name}}" name="weight_{{$group->name}}" value="0.00"
                                onchange="changeWeight(this)">
                        </div>
                    </div>
                    <label for="department" class="mb-2 mr-2">%</label>
                    <div class="btn-actions-pane-right">
                        <div role="group" class="btn-group-sm btn-group">
                            <button class="mb-2 mr-2 btn btn-danger" onclick="deleterule(this)"
                                data-group="{{$group->name}}" data-group-id="{{$group->id}}" disabled>Delete Selected
                                Rule</button>
                            <button class="mb-2 mr-2 btn btn-primary" data-toggle="modal" data-target="#modal-add-rule"
                                data-group="{{$group}}" disabled>Add new rule</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="mb-0 table table-sm {{$group->name}}" id="table-{{$group->name}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rule Name</th>
                                <th>Measurement</th>
                                <th>Base line</th>
                                <th>Max</th>
                                <th>Target config</th>
                                <th>Weight %</th>
                                <th>Numbers</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Weight :</td>
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
            <a href="{{route('kpi.template.index')}}" class="mb-2 mr-2 btn btn-warning">Go Back</a>
        </div>
    </div>
</div>
@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal-add-rule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Rule to : </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ruletemplate">
                    <input type="hidden" name="parent_rule_template_id" value="">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="rule-name" class="">Rule Name :</label>
                                <select id="validationRuleName" class="form-control-sm form-control" name="rule_id"
                                    required placeholder="placeholder" onchange="changerule(this)">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="base-line" class="">Base line
                                    : %</label><input name="base_line" id="validationBaseLine" placeholder="BaseLine"
                                    type="number" min="0" step="0.01" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="max-result" class="">Max
                                    : %</label>
                                <input name="max_result" id="validationMax" placeholder="Max result" type="number"
                                    min="0" step="0.01" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="target-config" class="">Amount
                                    :</label><input name="amount" id="validationTargetConfig"
                                    placeholder="Amount" type="number" min="0" step="0.1"
                                    class="form-control form-control-sm" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="target-config" class="">Target
                                    %
                                    :</label><input name="target_config" id="validationTargetConfig"
                                    placeholder="Target config" type="number" min="0" step="0.01"
                                    class="form-control form-control-sm" required></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="weight" class="">Weight limit
                                    100%:</label>
                                <input name="weight" id="validationWeight" placeholder="Weight" type="number" min="0"
                                    step="0.01" max="100" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="weight-category" class="">Weight
                                    category
                                    : %</label><input name="weight_category" id="validationWeightCategory"
                                    placeholder="Weight category" type="number" min="0" step="0.01"
                                    class="form-control form-control-sm" readonly></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="subMitForm()">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
<script>
    var template = {!!json_encode($template)!!}
    var rules = {!!json_encode($rules)!!}
    var temp_rules = [];
</script>
<script src="{{asset('assets\js\kpi\ruleTemplate\create.js')}}" defer></script>
<script>
</script>
@endsection