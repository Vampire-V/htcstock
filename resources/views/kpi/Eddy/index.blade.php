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
                                <input type="text" class="form-control form-control-sm" value="{{Auth::user()->name}}"
                                    placeholder="User" aria-describedby="inputGroupPrepend" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Division">Division</label>
                                <div class="input-group">
                                    <select name="division[]" id="division" class="form-control-sm form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="EMCGroup">EMC Group</label>
                                <div class="input-group">
                                    <select name="degree[]" id="degree" class="form-control-sm form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Month">Month</label>
                                <div class="input-group">
                                    <select name="period" id="period" class="form-control-sm form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Year">Year</label>
                                <select name="year[]" id="validationYear" class="form-control-sm form-control">
                                    <option value=""></option>
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
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Division</th>
                            <th>KPI</th>
                            <th class="text-center">Key-Task</th>
                            <th class="text-center">OMG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#345</td>
                            <td>name</td>
                            <td>Division</td>
                            <td>kpi</td>
                            <td>key-task</td>
                            <td>omg</td>
                        </tr>
                        <tr>
                            <td>#345</td>
                            <td>name</td>
                            <td>Division</td>
                            <td>kpi</td>
                            <td>key-task</td>
                            <td>omg</td>
                        </tr>
                        <tr>
                            <td>#345</td>
                            <td>name</td>
                            <td>Division</td>
                            <td>kpi</td>
                            <td>key-task</td>
                            <td>omg</td>
                        </tr>
                        <tr>
                            <td>#345</td>
                            <td>name</td>
                            <td>Division</td>
                            <td>kpi</td>
                            <td>key-task</td>
                            <td>omg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-block text-center card-footer">
                <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                        class="pe-7s-trash btn-icon-wrapper"> </i></button>
                <button class="btn-wide btn btn-success">Save</button>
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