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
    .badge:hover{
        background-color: #8b1631;
    }
    .badge{
        font-size: 100% !important;
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
            <div>Config Dead Line
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

{{-- tabs --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{__('Setting Dead Line')}}
            <div class="btn-actions-pane">
                <div role="group" class="btn-group-sm btn-group">
                </div>
            </div>
            <div class="btn-actions-pane-right">
                <div role="group" class="btn-group-sm btn-group">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="mb-0 table table-sm" id="table-steing-action">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Day</th>
                            <th>Remark</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div id="reload" class="reload"></div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal-dead-line" tabindex="-1" role="dialog" aria-labelledby="ModalLabelDeadLine"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelDeadLine">User Authorization</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-authorization">
                    <input type="hidden" name="action" id="action">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="User" class="">Division :</label>
                                <select id="division_id" class="form-control-sm form-control" name="division_id" required
                                onchange="division_change(this)">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="User" class="">User :</label>
                                <select id="user" class="form-control-sm form-control" name="user" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group">
                                <button type="button" onclick="attach_authorized()" class="btn btn-sm btn-success mt-4">Add</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Day" class="">Day :</label>
                                <select id="day" class="form-control-sm form-control" name="day" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group">
                                <button type="button" onclick="edit_day()" class="btn btn-sm btn-success mt-4">update</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="reload" class=""></div>
                <ul id="ul-sser-authorization">
                    
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" >Add</button> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\eddy\setting.js')}}" defer></script>
@endsection