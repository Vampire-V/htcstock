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
            <div>Staff Data
                <div class="page-title-subheading">This is an example staff data created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Detail</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name :</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$staff->name }}" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="department">Department :</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Department"
                                value="{{$staff->department->name}}" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="position">Position :</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Position"
                                value="{{$staff->positions->name}}" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year">Year :</label>
                            <select name="year" id="validationYear" class="form-control-sm form-control"
                                onchange="this.form.submit()">
                                @foreach (range(date('Y'), $start_year) as $year)
                                <option value="{{$year}}" @if (intval($selectedYear)===$year) selected @endif>{{$year}}
                                </option>
                                @endforeach
                            </select>
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
            <h5 class="card-title">Evaluation Forms</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($periods)
                        @foreach ($periods as $key => $period)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$period->name}}</td>
                            <td>
                                <span class="{{Helper::kpiStatusBadge($period->evaluate->status)}}">
                                    {{$period->evaluate->status}}
                                </span>
                            </td>
                            <td>
                                @if ($period->evaluate->status)
                                <form id="{{$key}}-remove"
                                    action="{{route('kpi.evaluate.destroy',[$staff->id,$period->id,$period->evaluate->id])}}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="mb-2 mr-2 btn btn-sm btn-outline-danger"
                                    onclick="event.preventDefault(); document.getElementById('{{$key.'-remove'}}').submit();">Remove</button>
                                @endif
                                <a href="{{$period->evaluate->status ? route('kpi.evaluate.edit',[$staff->id,$period->id,$period->evaluate->id]) : route('kpi.evaluate.create',[$staff->id,$period->id]) }}"
                                    class="mb-2 mr-2 btn btn-sm btn-outline-info">{{$period->evaluate->status ? "Edit" : "Create"}}
                                </a>
                            </td>
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
<script src="{{asset('assets\js\kpi\evaluationForm\index.js')}}" defer></script>
@endsection