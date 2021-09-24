@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    label {
        font-weight: bold;
    }

    label span {
        color: red;
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
            <div>Self Evaluate
                <div class="page-title-subheading">This is an example self evaluate created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="modal" title="Click" data-placement="top"
                class="btn-shadow mr-3 btn btn-dark no-disable" data-target="#comment-modal" id="show-comment">
                <span class="fa fa-commenting">&nbsp;Comment</span>
            </button>
            @if ($isAdmin)
            <button type="button" data-toggle="modal" title="Click" data-placement="top"
                class="btn-shadow mr-3 btn btn-dark no-disable" data-target="#history-modal" id="show-history">
                <span class="fa fa-history">&nbsp;History</span>
            </button>
            @endif

        </div>
    </div>
</div>
{{-- Display user detail --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h5 class="card-title">{{$evaluate->targetperiod->name}} {{$evaluate->targetperiod->year}}</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>status : <span class="{{Helper::kpiStatusBadge($evaluate->status)}}"> {{$evaluate->status}}
                            </span></h5>
                        {{$current ? $current->approveBy->name : null}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="staffName">Staff Name</label>
                                <input type="text" class="form-control form-control-sm" id="staffName"
                                    placeholder="Staff Name" value="{{$evaluate->user->name }}" readonly>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Department">Department</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="Department"
                                        value="{{$evaluate->user->department->name}}" placeholder="Department"
                                        aria-describedby="inputGroupPrepend" readonly>
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Position">Position</label>
                                <input type="text" class="form-control form-control-sm" id="Position"
                                    placeholder="Position" value="{{$evaluate->user->positions->name}}" disabled>
                                <div class="invalid-feedback">
                                    Please provide a valid city.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Template">Template</label>
                                <input type="text" class="form-control form-control-sm" id="Template"
                                    placeholder="Template" value="{{$evaluate->template->name}}" disabled>
                                <div class="invalid-feedback">
                                    Please provide a valid city.
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
</div>
@isset($category)
<div id="group-table">
    @foreach ($category as $group)
    <div class="row">
        <div class="col-lg-12">
            <div class="main-card mb-3 card">
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
                        <label for="Reduce" class="mb-2 mr-2">(ตัดคะแนน) :</label>
                        <div role="group" class="btn-group-sm btn-group">
                            <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="0.01"
                                value="0" id="{{str_replace("-","_",$group->name)}}_reduce"
                                name="{{str_replace("-","_",$group->name)}}_reduce" @cannot('super-admin') readonly
                                @endcannot> %
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-0 table table-sm table-bordered" id="table-{{$group->name}}">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Rule Name</th>
                                    <th>Description</th>
                                    <th>Base Line %</th>
                                    <th>Max %</th>
                                    <th>Weight %</th>
                                    <th>Target Amount</th>
                                    <th>Target %</th>
                                    <th>Actual Amount</th>
                                    <th>Actual %</th>
                                    <th>%Ach</th>
                                    <th>%Cal</th>
                                    <th>#</th>
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
                                    <td style="text-align: right;">Weight</td>
                                    <td>0.00</td>
                                    <td></td>
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
    </div>
    @endforeach
</div>
@endisset



{{-- Calculation Summary --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Calculation Summary</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-sm table-bordered" id="table-calculation">
                        <thead class="thead-dark">
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
</div>
{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">

        </div>
    </div>
</div>
<div class="page-title-actions">
    @if ($canOperation)
    <button class="mb-2 mr-2 btn btn-alternate no-disable" onclick="download()">Download</button>
    @endif

    <button class="mb-2 mr-2 btn btn-primary" id="submit" onclick="submit()">Save</button>
    <button class="mb-2 mr-2 btn btn-success" id="submit-to-user" onclick="submitToManager()">Save & Send to
        manager</button>
</div>

@endsection

{{-- @section('modal')
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
@endsection --}}

@section('modal')
{{-- Modal --}}

<div class="modal fade" id="switch-rule-modal" tabindex="-1" role="dialog" aria-labelledby="switch-rule-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="switch-rule-modal-label">New Rule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <input type="hidden" id="current_item" name="current_item">
                <form id="form-rule">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="rule-name" class="">Rule Name
                                    :</label>
                                <select id="rule_name" class="form-control form-control-sm" name="rule_name">
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changerule()">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="comment-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comment-modal-label">Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <div class="col-md-12">
                    <textarea class="form-control" name="comment" id="comment"
                        rows="5">{{$evaluate->comment}}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" >Add</button> --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="history-modal" tabindex="-1" role="dialog" aria-labelledby="history-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="history-modal-label">History evaluate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @isset($history)
                <div class="table-responsive">
                    <table class="mb-0 table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>By</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$item->status}}</td>
                                <td>{{$item->comment}}</td>
                                <td>{{$item->createdBy->name}}</td>
                                <td>{{$item->ip}}</td>
                                <td>{{$item->device}}</td>
                                <td>{{$item->created_at->diffForHumans()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endisset

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" >Add</button> --}}
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
<script defer>
    // variable
    const auth = {!!json_encode(Auth::user())!!}
    const evaluate = {!!json_encode($evaluate)!!}
    const weight_group = {!!json_encode($weight_group)!!}
    const operation = {!!json_encode($canOperation)!!}
    const admin = {!!json_encode($isAdmin)!!}
</script>
<script src="{{asset('assets\js\kpi\evaluationSelf\evaluate.js')}}" defer></script>
@endsection