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
{{-- end title  --}}
@can('admin-kpi')
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">Form search</div>
        <div class="card-body">
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name</label>
                            <select name="user[]" id="user" class="form-control form-control-sm">
                                @isset($users)
                                @foreach ($users as $item)
                                <option value="{{$item->id}}" @if ($selectedUser->contains($item->id))
                                    selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="Year">Year</label>
                            <select name="year[]" id="validationYear" class="form-control-sm form-control">
                                @foreach (range(date('Y'), $start_year) as $year)
                                <option value="{{$year}}" @if ($selectedYear->contains($year))
                                    selected
                                    @endif>{{$year}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                        </div>
                        <div class="col-md-1" style="display: flex; justify-content: center; align-items: center;  ">
                            <button class="btn btn-primary btn-sm mt-3" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@elsecan('user-kpi')
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">Form search</div>
        <div class="card-body">
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" value="{{Auth::user()->name}}"
                                placeholder="User" aria-describedby="inputGroupPrepend" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{Auth::user()->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" readonly>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{Auth::user()->positions->name}}" readonly>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="Year">Year</label>
                            <select name="year[]" id="validationYear" class="form-control-sm form-control">
                                @foreach (range(date('Y'), $start_year) as $year)
                                <option value="{{$year}}" @if ($selectedYear->contains($year))
                                    selected
                                    @endif>{{$year}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                        </div>
                        <div class="col-md-1" style="display: flex; justify-content: center; align-items: center;  ">
                            <button class="btn btn-primary " type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            <h5 class="card-title">Self Evaluate List</h5>
            <div class="btn-actions-pane">
                <div role="group" class="btn-group-sm btn-group">
                </div>
            </div>
            <div class="btn-actions-pane-right">
                <div role="group" class="btn-group-sm btn-group">
                    @can('for-superadmin')
                    {{-- <a href="{{route('kpi.evaluate.create_new')}}" class="btn-shadow btn btn-info mb-2 mr-2">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="pe-7s-plus"></i>
                        </span>
                        Create
                    </a>
                    <button onclick="report_excel()" class="btn-shadow btn btn-warning mb-2 mr-2">
                        <i class="fa fa-fw" aria-hidden="true" title="Copy to use file-excel-o"></i>
                        excel
                    </button> --}}
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Period</th>
                            <th style="width: 10px;">Year</th>
                            <th>Status</th>
                            <th style="width: 10px;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluates)
                        @foreach ($evaluates as $key => $evaluate)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$evaluate->targetperiod->name}}</td>
                            <td>{{$evaluate->targetperiod->year}}</td>
                            <td>
                                <div class="{{Helper::kpiStatusBadge($evaluate->status)}}"> {{$evaluate->status}} </div>
                            </td>
                            <td><a href="{{route('kpi.self-evaluation.edit',$evaluate->id)}}"
                                    class="mb-2 mr-2 btn-transition btn btn-outline-info">view
                                </a></td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\evaluationSelf\index.js')}}" defer></script>
@endsection