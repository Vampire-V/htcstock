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
        <div class="card-header">{{__('Form search')}}</div>
        <div class="card-body">
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    @can('parent-admin-kpi')
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="department">Division :</label>
                            <select class="form-control form-control-sm" name="division_id[]" id="division_id" multiple>
                                <option value=""></option>
                                @foreach ($divisions as $division)
                                <option value="{{$division->id}}" @if($selectedDivision->contains($division->id))
                                    selected @endif>{{$division->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="department">Department :</label>
                            <select class="form-control form-control-sm" name="department_id[]" id="department_id" multiple>
                                <option value=""></option>
                                @foreach ($departments as $department)
                                <option value="{{$department->id}}" @if($selectedDepartment->contains($department->id))
                                    selected @endif>{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="department">EMC Group :</label>
                            <select class="form-control form-control-sm" name="degree[]" id="degree" multiple>
                                <option value=""></option>
                                @foreach ($emc_group as $emc)
                                <option value="{{$emc}}" @if($selectedEmc->contains($emc))
                                    selected @endif>{{$emc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan

                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="department">User :</label>
                            <select class="form-control form-control-sm" name="user[]" id="user" multiple>
                                <option value=""></option>
                                @foreach ($users as $item)
                                <option value="{{$item->id}}" @if($selectedUser->contains($item->id))
                                    selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
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
                                <option value="{{date('m', strtotime($month->name." 1 2021"))}}" @if($selectedPeriod->
                                    contains(date('m', strtotime($month->name." 1 2021")))) selected
                                    @endif>{{$month->name}}</option>
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
        <div class="card-header">{{__('Staff List')}}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Year</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Next approval</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluates)
                        @foreach ($evaluates as $key => $evaluate)
                        @isset($evaluate->user)

                        <tr style="background-color: {{$evaluate->background}}" >
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$evaluate->user->name }}</td>
                            <td>{{$evaluate->user->department->name}}</td>
                            <td>{{$evaluate->user->positions->name}}</td>
                            <td>{{$evaluate->targetperiod->year}}</td>
                            <td>{{$evaluate->targetperiod->name}}</td>
                            <td><span class="{{Helper::kpiStatusBadge($evaluate->status)}}">{{$evaluate->status}}</span>
                            </td>
                            <td>
                                @php
                                    $nextAp = $evaluate->userApprove->where('level',$evaluate->next_level)->first()
                                    
                                @endphp
                                {{$nextAp ? $nextAp->approveBy->name : "หาไม่เจอให้ IT ตรวจสอบ"}}
                                {{-- ->approveBy->name --}}
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
                {{ $evaluates->appends($query)->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\evaluationReview\index.js')}}" defer></script>
@endsection
