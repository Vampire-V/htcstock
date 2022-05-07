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
            <div>Department in charge
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
{{-- <div class="row">
    <div class="col-12">
        <div class="main-card mb-3 card">
            <div class="card-header"> {{__('Form search')}} </div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-2 mb-2">
                                <label for="staffName">Staff Name</label>
                                <select name="users_where[]" id="users_where" class="form-control-sm form-control" multiple>
                                    <option value=""></option>
                                    @isset($users_drop)
                                    @foreach ($users_drop as $user)
                                    <option value="{{$user->id}}"
                                        @if ($sel_user->contains($user->id))
                                        selected
                                    @endif>{{$user->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Department">Department</label>
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
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="EMCGroup">EMC Group</label>
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
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Month">Month</label>
                                <select name="month" id="month" class="form-control-sm form-control">
                                    @foreach (range(1, 12) as $m)
                                    <option value="{{Helper::convertToMonthNumber($m)}}"
                                    @if ($sel_month===Helper::convertToMonthNumber($m)) selected @endif>
                                        {{Helper::convertToMonthName($m)}}</option>
                                    @endforeach
                                </select>
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
</div> --}}
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Form department</div>
            <div class="card-body">
                <form action="" method="post" class="form-inline">
                    @csrf
                    <label class="sr-only" for="inlineFormInputName2">Name</label>
                    <select id="dept" class="form-control-sm form-control mr-sm-2"
                            name="dept[]" multiple>
                            @isset($departments)
                            @foreach ($departments as $item)
                            <option value="{{$item->id}}" >{{$item->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                    <button type="submit" class="btn btn-primary mb-2" >Add department</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Department
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="active btn btn-focus">Last Week</button> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <ul>
                        @isset($deptInCharge)
                        @foreach ($deptInCharge as $item)
                        <li>
                            <form id="remove-dept-form-{{$item->id}}" action="/kpi/department-in-charge/{{$item->id}}" method="POST"
                                        style="display: none;">
                                        @method("DELETE")
                                        @csrf
                                    </form>
                            <button class="btn btn-sm btn-danger mr-2 mb-2" onclick="event.preventDefault();
                                document.getElementById('remove-dept-form-{{$item->id}}').submit();">remove</button>&nbsp;&nbsp;&nbsp;{{$item->name}}</li>
                        @endforeach
                        @endisset
                    </ul>
                    {{-- <table class="align-middle mb-0 table table-borderless table-striped table-hover"
                        id="table-dept-result">
                        <thead class="thead-dark">
                            <tr>
                                <th>EMC Group</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Step 1 (1-4)</th>
                                <th>Step 2 (1-11)</th>
                                <th>Step 3 (1-16)</th>
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
                                    {{$user->time_first}}
                                    @else
                                    &#x274C;
                                    @endif</td>
                                <td>@if ($user->second)
                                    &#10004;
                                    {{$user->time_second}}
                                    @else
                                    &#x274C;
                                    @endif</td>
                                <td>@if ($user->third)
                                    &#10004;
                                    {{$user->time_third}}
                                    @else
                                    &#x274C;
                                    @endif</td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table> --}}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#dept").select2({
            placeholder: 'Select department',
            allowClear: true,
            // dropdownParent: $('#modal-dead-line')
        })
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);
})();

const removeDept = (e) => {

    fetch(`${window.location.origin}${window.location.pathname}/${e.dataset.id}`, { method: 'DELETE',headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ _token: getMeta('csrf-token') }) })
    .then((res) => {
        console.log(res);
    })
    .catch(err => {
        console.error(err);
    });
}
function getMeta(metaName) {
  const metas = document.getElementsByTagName('meta');

  for (let i = 0; i < metas.length; i++) {
    if (metas[i].getAttribute('name') === metaName) {
      return metas[i].getAttribute('content');
    }
  }

  return '';
}
</script>
@endsection
