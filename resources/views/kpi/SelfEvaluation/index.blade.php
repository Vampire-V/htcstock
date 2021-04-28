@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi');
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

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Form search</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$user->name}}" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{$user->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" readonly>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{$user->positions->name}}" readonly>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="Year">Year</label>
                            <select name="year[]" id="validationYear" class="form-control-sm form-control" multiple>
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

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Self Evaluate List</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>#</th>
                            {{-- <th>Table heading</th>
                            <th>Table heading</th>
                            <th>Table heading</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluates)
                        @foreach ($evaluates as $key => $evaluate)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$evaluate->targetperiod->name}}</td>
                            <td>{{$evaluate->status}}</td>
                            <td><a href="{{route('kpi.self-evaluation.edit',$evaluate->id)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">view
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