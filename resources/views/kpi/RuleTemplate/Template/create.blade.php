@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    .hide {
        display: none;
    }
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
            <div>Template Management
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
        <div class="card-header">Teamplate</div>
        <div class="card-body">
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate method="POST" action="{{route('kpi.template.store')}}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ruleTemplateName">Template name :</label>
                            <input type="text" class="form-control form-control-sm" id="validationTemplate" name="name"
                                value="" placeholder="Rule template name" required>
                            <div class="invalid-feedback">
                                Please provide a valid Rule template.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="mb-2 mr-2 btn btn-sm btn-primary mt-4" type="button"
                                onclick="create_template()">create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@isset($category)
<div id="all-table" class="hide">
    @foreach ($category as $group)
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5 class="card-title">{{$group->name}}</h5>
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="mb-2 mr-2 btn btn-danger" onclick="remove('table-{{$group->name}}')"
                            data-group="{{$group->name}}" data-group-id="{{$group->id}}">Delete Selected
                            Rule</button>
                        <button class="mb-2 mr-2 btn btn-primary" data-toggle="modal" data-target="#modal-add-rule"
                            data-group="{{$group}}">Add new rule</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-sm {{$group->name}}" id="table-{{$group->name}}">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Rule Name</th>
                                <th>Base line %</th>
                                <th>Max %</th>
                                <th>Select</th>
                                <th>Sort</th>
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
            {{-- <button class="hide mb-2 mr-2 btn btn-success">Save</button> --}}
            {{-- <button class="mb-2 mr-2 btn btn-danger">Delete</button> --}}
            {{-- <button type="button" class="btn btn-success" id="showtoast">Show Toast</button> --}}
        </div>
    </div>
</div>
@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal-add-rule" tabindex="-1" role="dialog" aria-labelledby="rule-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rule-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <form id="form-rule">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="rule-name" class="">Rule Name: </label>
                                <select id="rule_name" class="form-control form-control-sm" name="rule_name">
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="add_rule()">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script>
</script>
<script src="{{asset('assets\js\kpi\ruleTemplate\create.js')}}" defer></script>

@endsection