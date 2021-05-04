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
            <div>Review Evaluate
                <div class="page-title-subheading">This is an example review evaluate created using
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

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Form search</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-2 mb-3">
                            <label for="department">Department :</label>
                            <input type="text" class="form-control form-control-sm" id="validationDepartment"
                                value="{{$user->department->name}}" readonly>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="position">Position :</label>
                            <input type="text" class="form-control form-control-sm" id="validationPosition"
                                value="{{$user->positions->name}}" readonly>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="status">Status :</label>
                            <select name="status[]" id="validationStatus" class="form-control-sm form-control" multiple>
                                @isset($status_list)
                                @foreach ($status_list as $status)
                                <option value="{{$status}}" @if($selectedStatus->contains($status))
                                    selected @endif>{{$status}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="year">Year :</label>
                            <select name="year[]" id="validationYear" class="form-control-sm form-control" multiple>
                                @foreach (range(date('Y'),$start_year) as $year)
                                <option value="{{$year}}" @if($selectedYear->contains($year))
                                    selected @endif>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="period">Period :</label>
                            <select name="period[]" id="validationPeriod" class="form-control-sm form-control" multiple>
                                @isset($months)
                                @foreach ($months as $month)
                                <option value="{{date('m', strtotime($month->name." 1 2021"))}}" @if($selectedPeriod->contains($month->name))
                                    selected @endif>{{$month->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button class="mb-2 mr-2 btn btn-primary mt-4 ml-3" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Staff List</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Year</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluates)
                        @foreach ($evaluates as $key => $evaluate)
                        @isset($evaluate->user)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$evaluate->user->name }}</td>
                            <td>{{$evaluate->user->department->name}}</td>
                            <td>{{$evaluate->user->positions->name}}</td>
                            <td>{{$evaluate->targetperiod->year}}</td>
                            <td>{{$evaluate->targetperiod->name}}</td>
                            <td><span class="{{Helper::kpiStatusBadge($evaluate->status)}}">{{$evaluate->status}}</span>
                            </td>
                            <td><a href="{{route('kpi.evaluation-review.edit',$evaluate->id)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">Review
                                </a></td>
                        </tr>
                        @endisset
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
<script src="{{asset('assets\js\kpi\evaluationReview\index.js')}}" defer></script>
@endsection