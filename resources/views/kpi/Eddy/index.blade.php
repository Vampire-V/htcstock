@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .input-sm {
        height: calc(1.8125rem + -7px);
        /* width: 50%; */
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
            <div>Eddy page
                <div class="page-title-subheading">This is an example set target created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
        </div>
    </div>
</div>

{{-- end title  --}}
<div class="row">
    <div class="col-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">search</h5>
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-2 mb-2">
                                <label for="staffName">Staff Name</label>
                                {{-- <div class="input-group"> --}}
                                <select name="users_where[]" id="users_where" class="form-control-sm form-control" multiple>
                                    <option value=""></option>
                                    @isset($users_drop)
                                    @foreach ($users_drop as $user)
                                    <option value="{{$user->id}}"
                                        @php
                                            // dd($sel_user,$user->id);
                                        @endphp
                                        @if ($sel_user->contains($user->id))
                                        selected
                                    @endif>{{$user->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Department">Department</label>
                                {{-- <div class="input-group"> --}}
                                <select name="department_where[]" id="department_where" class="form-control-sm form-control" multiple>
                                    <option value=""></option>
                                    @isset($departments)
                                    @foreach ($departments as $department)
                                    <option value="{{$department->id}}"
                                        @if ($sel_dept->contains($department->id))
                                        selected
                                    @endif>{{$department->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="EMCGroup">EMC Group</label>
                                {{-- <div class="input-group"> --}}
                                <select name="degree[]" id="degree" class="form-control-sm form-control" multiple>
                                    <option value=""></option>
                                    @isset($degree)
                                    @foreach ($degree as $item)
                                    <option value="{{$item}}" @if ($sel_degree->contains($item))
                                        selected
                                    @endif>{{$item}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Month">Month</label>
                                {{-- <div class="input-group"> --}}
                                <select name="month" id="month" class="form-control-sm form-control">
                                    @foreach (range(1, 12) as $m)
                                    <option value="{{Helper::convertToMonthNumber($m)}}" 
                                    @if ($sel_month===Helper::convertToMonthNumber($m)) selected @endif>
                                        {{Helper::convertToMonthName($m)}}</option>
                                    @endforeach
                                </select>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Year">Year</label>
                                <select name="year" id="year" class="form-control-sm form-control">
                                    @foreach (range(date('Y'), (date('Y') - 5)) as $year)
                                    <option value="{{$year}}" @if ($sel_year===$year) selected @endif>{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1"
                                style="display: flex; justify-content: center; align-items: center;  ">
                                <button class="btn btn-primary " type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Active Users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="active btn btn-focus">Last Week</button>
                        <button class="btn btn-focus">All Month</button> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover"
                        id="table-users-result">
                        <thead class="thead-dark">
                            <tr>
                                <th>EMC Group</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Step 1 (1-4)</th>
                                <th>Step 2 (1-10)</th>
                                <th>Step 3 (1-12)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($users)
                            @foreach ($users as $key => $user)
                            <tr>
                                <td class="text-left">{{$user->degree}}</td>
                                <td class="text-left">{{$user->name}}</td>
                                <td class="text-left">{{$user->department->name}}</td>
                                <td class="text-left">{{$user->positions->name}}</td>
                                <td> @if ($user->first)
                                    &#10004;
                                    @else
                                    &#x274C;
                                    @endif</td>
                                <td>@if ($user->second)
                                    &#10004;
                                    @else
                                    &#x274C;
                                    @endif</td>
                                <td>@if ($user->third)
                                    &#10004;
                                    @else
                                    &#x274C;
                                    @endif</td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>

</script>
<script src="{{asset('assets\js\kpi\eddy\index.js')}}" defer></script>
@endsection