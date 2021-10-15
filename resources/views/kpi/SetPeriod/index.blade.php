@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
    <style>
        label {
            font-weight: bold;
        }
        .table-sm th, .table-sm td {
            padding: 0rem;
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
            <div>Set Periods
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
    <div class="col-md-12 col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                    <h5 class="card-title">Search</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-2 mb-2">
                                <label for="Year">Period Name</label>
                                <select name="period[]" id="period" class="form-control-sm form-control" multiple>
                                    <option value="">Choose...</option>
                                    @foreach (range(1,12) as $month)
                                    <option value="{{date('m', mktime(0, 0, 0, $month, 1))}}"
                                    @if ($selectedPeriod->
                                        contains(date('m', mktime(0, 0, 0, $month, 1))))
                                        selected
                                        @endif>
                                        {{date('F', mktime(0, 0, 0, $month, 1))}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Year">Year</label>
                                <select name="year" id="year" class="form-control-sm form-control">
                                    <option value=""></option>
                                    @isset($years)
                                    @foreach ($years as $year)
                                    <option value="{{$year->year}}" @if ($selectedYear===$year->year) selected
                                        @endif>{{$year->year}}
                                    </option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-1 mb-1 text-center">
                                <button class="btn btn-primary mt-4" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <h5 class="card-title">Set Periods</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">

                        <a href="{{route('kpi.generate-month.auto')}}" class="btn-shadow btn btn-warning mb-2 mr-2">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="pe-7s-plus"></i>
                            </span>
                            Create a month for this year auto
                        </a>
                        {{-- <a href="{{route('kpi.set-period.create')}}" class="btn-shadow btn btn-info mb-2 mr-2">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="pe-7s-plus"></i>
                            </span>
                            Create
                        </a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-sm" id="table-set-actual">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Year</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($periods)
                            @foreach ($periods as $key => $period)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{ $period->name }}</td>
                                <td>{{$period->year}}</td>
                                <td><a href="{{route('kpi.set-period.edit',$period->id)}}"
                                        class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info btn-sm">Edit
                                    </a></td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                        <tfoot>
                        </tfoot>
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
    // variable
</script>
<script src="{{asset('assets\js\kpi\setPeriod\index.js')}}" defer></script>

@endsection
