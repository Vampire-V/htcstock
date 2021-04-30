@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.it');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>{{ __('itstock.requisition-accessorie.drawdown-list') }}
                <div class="page-title-subheading">This is an example dashboard created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
            <div class="d-inline-block">
                <a href="{{route('it.equipment.requisition.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    {{ __('itstock.requisition-accessorie.requisition') }}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{route('it.equipment.requisition.index')}}" method="GET">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationAccess_id" class="">{{ __('itstock.requisition-accessorie.equipment') }}</label>
                            <select class="form-control-sm form-control js-select-accessory-multiple" style="width: 100%"
                                name="accessory[]" multiple>
                                @isset($accessorys)
                                @foreach ($accessorys as $item)
                                <option value="{{$item->access_id}}" @if($selectedAccessorys->
                                    contains($item->access_id))
                                    selected @endif>{{$item->access_name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationCreated_at">{{ __('itstock.requisition-accessorie.a-date') }}</label>
                            <input type="date" class="form-control-sm form-control" id="validationSCreated_at" name="start_at"
                                value="{{$start_at}}" oninput="changeValue(this)">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationCreated_at">{{ __('itstock.requisition-accessorie.up-to-date') }}</label>
                            <input type="date" class="form-control-sm form-control" id="validationECreated_at" name="end_at"
                                value="{{$end_at}}" readonly>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn-shadow btn btn-info" type="submit" style="margin-top: 30px">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-business-time fa-w-20"></i>
                                </span>
                                {{ __('itstock.requisition-accessorie.search') }}</button>
                        </div>
                    </div>
                </form>
                <script>
                    if (document.getElementById("validationSCreated_at").value) {
                        document.getElementById("validationECreated_at").readOnly = false;
                    }
                </script>
                <script src="{{asset('assets\js\transactions\requisition.js')}}" defer></script>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">{{ __('itstock.requisition-accessorie.drawdown-list') }}</h5>
                <div class="table-responsive">
                    <table class="mb-0 table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('itstock.requisition-accessorie.equipment') }}</th>
                                <th>{{ __('itstock.requisition-accessorie.number') }}</th>
                                <th>{{ __('itstock.requisition-accessorie.request-creator-name') }}</th>
                                <th>{{ __('itstock.requisition-accessorie.a-date') }}</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($transactions)
                            @foreach ($transactions as $key => $item)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$item->accessorie->access_name}}</td>
                                <td>{{Helper::convertQty($item->qty)}}</td>
                                <td>{{$item->user->name }}</td>
                                <td>{{$item->created_at}}</td>
                                <td><a href="{{route('it.equipment.requisition.edit',$item->id)}}"><button type="button"
                                            class="btn btn-primary btn-sm float-left">{{ __('itstock.requisition-accessorie.detail') }}</button></a></td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                @isset($transactions)
                {{ $transactions->appends($query)->links() }}
                @endisset
            </div>
        </div>
    </div>
</div>
@stop