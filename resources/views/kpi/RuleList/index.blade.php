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
            <div>Rule Search
                <div class="page-title-subheading">This is an example rule search created using
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
                {{-- <a href="{{route('kpi.rule-list.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Create
                </a> --}}
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Rule Search</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="ruleName">Rule Name :</label>
                            <input type="text" class="form-control form-control-sm" id="ruleName"
                                placeholder="Rule Name" name="name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ruleCategory">Rule Category :</label>
                            <select id="validationRuleCategory" class="form-control-sm form-control" name="category_id[]" multiple>
                                <option value="">-----</option>
                                @isset($category)
                                @forelse ($category as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @empty
                                <p>No Rule Category</p>
                                @endforelse
                                @endisset
                            </select>
                        </div>
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
        <div class="card-body">
            
            <div class="card-header">
                <h5 class="card-title">Rule List</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="mb-2 mr-2 btn btn-danger" onclick="deleteRuleTemp()">Delete Selected
                            Rule</button>
                        <button class="mb-2 mr-2 btn btn-primary" data-toggle="modal" data-target="#ruleModal">Add
                            new rule</button> --}}
                        <a href="{{route('kpi.rule-list.create')}}" class="btn-shadow btn btn-info mb-2 mr-2">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-business-time fa-w-20"></i>
                            </span>
                            Create
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rule Name</th>
                            <th>Rule Category</th>
                            <th>Calculate Type</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($rules)
                        @foreach ($rules as $key => $item)
                        <tr>
                            <th scope="row">{{$key + 1}}</th>
                            <td>{{$item->name}}</td>
                            <td>{{$item->category->name}}</td>
                            <td>{{$item->calculate_type}}</td>
                            <td><a href="{{route('kpi.rule-list.edit',$item->id)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">Edit
                                </a></td>
                        </tr>
                        @endforeach
                        @endisset
                        {{-- <tr>
                            <th scope="row">1</th>
                            <td>Seller Target</td>
                            <td></td>
                            <td><a href="{{route('kpi.rule-list.edit',1)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">Edit
                                </a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Quality Controll</td>
                            <td></td>
                            <td><a href="{{route('kpi.rule-list.edit',1)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">Edit
                                </a></td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\rule\index.js')}}" defer></script>
@endsection