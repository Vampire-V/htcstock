@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .bs-example {
        margin: 20px;
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
            <div>Rule Template Detail Management
                <div class="page-title-subheading">This is an example rule management created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Rule Management</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate method="POST" action="{{route('kpi.template.store')}}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ruleTemplateName">Rule template name :</label>
                            <input type="text" class="form-control form-control-sm" id="validationTemplate" name="name"
                                value="" placeholder="Rule template name" required>
                            <div class="invalid-feedback">
                                Please provide a valid Rule template.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="department">Department :</label>
                            <select id="validationDepartment" class="form-control-sm form-control" name="department_id"
                                required>
                                <option value="">Choose...</option>
                                @isset($departments)
                                @foreach ($departments as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Department.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            {{-- <button class="mb-2 mr-2 btn btn-primary mt-4" type="submit">Save</button> --}}
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>


{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-success">Save</button>
            {{-- <button class="mb-2 mr-2 btn btn-danger">Delete</button> --}}
            {{-- <button type="button" class="btn btn-success" id="showtoast">Show Toast</button> --}}
        </div>
    </div>
</div>
</form>
@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\ruleTemplate\createTemplate.js')}}" defer></script>

@endsection