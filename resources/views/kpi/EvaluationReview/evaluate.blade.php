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
            <div>Evaluate Review
                <div class="page-title-subheading">This is an example self evaluate created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            {{-- @if ($canAdmin) --}}
            <button type="button" data-toggle="modal" title="Click" data-placement="top"
                class="btn-shadow mr-3 btn btn-dark no-disable" data-target="#history-modal" id="show-history">
                <span class="fa fa-history">&nbsp;History</span>
            </button>
            {{-- @endif --}}
        </div>
    </div>
</div>

{{-- Display user detail --}}
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
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{$evaluate->user->positions->name}}" readonly>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Template">Template</label>
                            <input type="text" class="form-control form-control-sm" id="Template" placeholder="Template"
                                value="{{$evaluate->template->name}}" readonly>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@isset($category)
<div id="group-table">
    @foreach ($category as $group)
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <label for="department" class="mb-2 mr-2">{{$group->name}} (Weight) :</label>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                        <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="0.01"
                            id="weight-{{$group->name}}" name="weight_{{str_replace("-","_",$group->name)}}" readonly> %
                    </div>
                </div>

                <div class="btn-actions-pane-right">
                    <label for="Reduce" class="mb-2 mr-2">(ตัดคะแนน HOD):</label>
                    <div role="group" class="btn-group-sm btn-group">
                        <input class="mb-1 mr-1 form-control-sm form-control" type="number" min="0" step="0.01"
                            value="0" id="{{str_replace("-","_",$group->name)}}_reduce_hod"
                            name="{{str_replace("-","_",$group->name)}}_reduce_hod"  @cannot('parent-admin-kpi') readonly
                            @endcannot onchange="set_reduce(this)">
                    </div>
                    <label for="Reduce" class="mb-2 mr-2">(ตัดคะแนนส่งช้า):</label>
                    <div role="group" class="btn-group-sm btn-group">
                        <input class="mb-1 mr-1 form-control-sm form-control" type="number" min="0" step="0.01"
                            value="0" id="{{str_replace("-","_",$group->name)}}_reduce"
                            name="{{str_replace("-","_",$group->name)}}_reduce" @cannot('parent-admin-kpi') readonly
                            @endcannot onchange="set_reduce(this)"> %
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
                                <th>Remark</th>
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
    @endforeach
</div>
@endisset


{{-- Calculation Summary --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">{{__('Calculation Summary')}}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-sm" id="table-calculation">
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

{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">

        </div>
    </div>
</div>
<div class="page-title-actions fiexd-btn-botton">
    @if ($evaluate->status === 'Approved')
    @if ($canAdmin)
    <button class="mb-2 mr-2 btn btn-success" onclick="save(this)">Save</button>
    @endif
    @else
    <button class="mb-2 mr-2 btn btn-primary" onclick="approve(this)">Approve</button>
    <button class="mb-2 mr-2 btn btn-warning" onclick="reject(this)">Reject</button>
    @endif
</div>

@endsection

@section('modal')
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
<script defer>
    // variable
    const auth = {!!json_encode(Auth::user())!!}
    const evaluate = {!!json_encode($evaluate)!!}
    const current = {!!json_encode($current)!!}
    const can_input = {!!json_encode($canInput)!!}
</script>
<script src="{{asset('assets\js\kpi\evaluationReview\evaluate.js')}}" defer></script>
@endsection
