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
            <div>Create Period
                <div class="page-title-subheading">This is an example rule management created using
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
            <h5 class="card-title">Form Period</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate action="{{route('kpi.set-period.store')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ForName">Name</label>
                            <select class="form-control" name="name" id="name" required>
                                @foreach (range(1,12) as $month)
                                <option value="{{date('m', mktime(0, 0, 0, $month, 1))}}">
                                    {{date('F', mktime(0, 0, 0, $month, 1))}} </option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid name.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ForYear">Year</label>
                            <select class="form-control" name="year" id="year" required>
                                @foreach (range(date('Y'),date('Y')+5) as $year)
                                <option value="{{$year}}">
                                    {{$year}}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please provide a valid year.
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\setPeriod\create.js')}}" defer></script>
@endsection