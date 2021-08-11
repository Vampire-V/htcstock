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
            <div>Rule Template Search
                <div class="page-title-subheading">This is an example rule template search created using
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
        <div class="card-header">Form Search</div>
        <div class="card-body">
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ruleTemplateName">Rule template name :</label>
                            <select id="validationRuleTemplate" class="form-control-sm form-control"
                                name="template_id[]" multiple>
                                @isset($dropdowntem)
                                @foreach ($dropdowntem as $item)
                                <option value="{{$item->id}}" @if ($selectRuleTemp->contains($item->id))
                                    selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        {{-- <div class="col-md-4 mb-3">
                            <label for="department">Department :</label>
                            <select id="validationDepartment" class="form-control-sm form-control"
                                name="department_id[]" multiple>
                                @isset($departments)
                                @foreach ($departments as $item)
                                <option value="{{$item->id}}" @if ($selectDepartment->contains($item->id))
                                    selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div> --}}
                        <div class="col-md-4 mb-3">
                            <button class="mb-2 mr-2 btn btn-primary mt-4">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            Rule Template List
            <div class="btn-actions-pane">
                <div role="group" class="btn-group-sm btn-group">
                </div>
            </div>
            <div class="btn-actions-pane-right">
                <div role="group" class="btn-group-sm btn-group">
                    <a href="{{route('kpi.template.create')}}" class="btn-shadow btn btn-info mb-2 mr-2">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="pe-7s-plus"></i>
                        </span>
                        Create
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Rule Template Name</th>
                            {{-- <th>Department</th> --}}
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($templates)
                        @foreach ($templates as $key => $template)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$template->name}}</td>
                            {{-- <td>{{$template->department->name}}</td> --}}
                            <td>
                                <a href="{{route('kpi.template.edit',[$template->id])}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-sm btn-info">Edit
                                </a>
                                <a href="{{ route('kpi.template.destroy',['template' => $template->id]) }}">
                                    <button type="button" onclick="event.preventDefault();
                                    document.getElementById('delete-template-form{{$template->id}}').submit();"
                                        class="btn btn-sm btn-danger">Delete</button>
                                </a>

                                <form id="delete-template-form{{$template->id}}"
                                    action="{{ route('kpi.template.destroy',['template' => $template->id]) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button class="mb-2 mr-2 btn btn-sm btn-warning" data-template="{{$template->id}}" data-toggle="modal"
                                    data-target="#transfer-modal">Transfer</button>
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

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="transfer-modal" tabindex="-1" role="dialog" aria-labelledby="transfer-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transfer-modal-label">Transfer To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <form id="form-transfer">
                    <input type="hidden" name="template" id="template">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="User" class="">User
                                    :</label>
                                <select id="user" class="form-control form-control-sm" name="user">
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="transfer_template()">Transfer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\ruleTemplate\index.js')}}" defer></script>
@endsection