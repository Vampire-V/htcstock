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
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="top"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
        </div>
    </div>
</div>
{{-- Display user detail --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h5 class="card-title">Year : {{$year}} , Month : {{$month_rang->join(', ')}}</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>
                            {{-- status : <span class="{{Helper::kpiStatusBadge($evaluate->status)}}"> {{$evaluate->status}} </span> --}}
                        </h5>
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
                                    placeholder="Staff Name" value="{{$evaluate->user->name}}" readonly>
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
                            {{-- <div class="col-md-3 mb-3">
                            <label for="Template">Template</label>
                            <input type="text" class="form-control form-control-sm" id="Template" placeholder="Template"
                                value="" disabled>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div> --}}
                        </div>
                        <div class="form-row">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @isset($category) --}}
<div id="group-table">
    @foreach ($group_category as $key => $items)
    <div class="row">
        <div class="col-lg-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <label for="department" class="mb-2 mr-2">{{$key}} (Weight) :</label>
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="0.01"
                                id="weight_{{$key}}" name="weight_{{str_replace("-","_",$key)}}"
                                value="{{$quarter_weight[$loop->index]}}" readonly> %
                        </div>
                    </div>
                    <div class="btn-actions-pane-right">

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-0 table table-sm" id="table-{{$key}}">
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
                                @foreach ($items->sortBy("rule_id") as $item)
                                @if ($key !== "omg")
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td data-toggle="tooltip" title="" data-placement="top" class="truncate" data-original-title="{{$item->rule->name}}">{{$item->rule->name}}</td>
                                    <td data-toggle="tooltip" title="" data-placement="top" class="truncate" data-original-title="{{$item->rule->description}}">{{$item->rule->description}}</td>
                                    <td>{{Helper::decimal($item->base_line)}} %</td>
                                    <td>{{Helper::decimal($item->max_result)}} %</td>
                                    <td>{{Helper::decimal($item->weight)}} %</td>
                                    <td>{{Helper::decimal($item->target)}}</td>
                                    <td>{{Helper::decimal($item->target_pc)}} %</td>
                                    <td>{{Helper::decimal($item->actual)}}</td>
                                    <td>{{Helper::decimal($item->actual_pc)}} %</td>
                                    <td>{{Helper::decimal($item->ach)}} %</td>
                                    <td>{{Helper::decimal($item->cal)}} %</td>
                                    <td></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if ($key !== "omg")
                                <tr>
                                    <th scope="row"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;">Weight</td>
                                    <td>{{Helper::decimal($items->reduce(fn($carry, $item) => $carry + $item->weight,0))}} %</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @php
                                    $total_detail = 0;
                                        if ($key === "kpi") {
                                            $total_detail = $items->reduce(fn($carry, $item) => $carry + $item->cal,0) - $evaluate->kpi_reduce;
                                        } else if ($key === "key-task") {
                                            $total_detail = $items->reduce(fn($carry, $item) => $carry + $item->cal,0) - $evaluate->key_task_reduce;
                                        }

                                    @endphp
                                    <td>{{Helper::decimal($total_detail)}} %</td>
                                    <td></td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
{{-- @endisset --}}

{{-- Calculation Summary --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Calculation Summary</div>
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
                            @foreach ($summary as $item)
                            <tr>
                                <th>{{$item->key}}</th>
                                <td>{{Helper::decimal($item->weight)}} %</td>
                                <td>{{Helper::decimal($item->cal)}} %</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row">Total</th>
                                <td>{{Helper::decimal($summary->reduce(fn($c,$item) => $c+=$item->weight,0))}} %</td>
                                <td>{{Helper::decimal($summary->reduce(fn($c,$item) => $c+=$item->cal,0))}} %</td>
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
            @can('admin-kpi')
            {{-- <button class="mb-2 mr-2 btn btn-alternate no-disable" onclick="download()">Download</button> --}}
            @endcan
            {{-- <button class="mb-2 mr-2 btn btn-primary" id="submit" onclick="submit()">Save</button>
            <button class="mb-2 mr-2 btn btn-success" id="submit-to-user" onclick="submitToManager()">Save & Send to
                manager</button> --}}
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
    const auth = {!!json_encode($evaluate->user)!!}
    const evaluate = {!!json_encode($evaluate)!!}
</script>
{{-- <script src="{{asset('assets\js\kpi\evaluationSelf\quarter.js')}}" defer></script> --}}
@endsection
