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
            <div>{{ __('itstock.buy-accessorie.purchase-list') }}
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
                <a href="{{route('it.equipment.buy.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    {{ __('itstock.buy-accessorie.buy') }}</a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="#" method="GET">
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationAccess_id" class="">{{ __('itstock.buy-accessorie.equipment') }}</label>
                        <select class="form-control-sm form-control js-select-accessory-multiple" style="width: 100%"
                            name="accessory[]" multiple>
                            @isset($accessorys)
                            @foreach ($accessorys as $item)
                            <option value="{{$item->access_id}}" @if($selectedAccessorys->contains($item->access_id))
                                selected @endif>{{$item->access_name}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationIr_no">{{ __('itstock.buy-accessorie.ir-no') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationIr_no" name="ir_no"
                            value="{{$ir_no}}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationPo_no">{{ __('itstock.buy-accessorie.po-no') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationPo_no" name="po_no"
                            value="{{$po_no}}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationCreated_at">{{ __('itstock.buy-accessorie.a-date') }}</label>
                        <input type="date" class="form-control-sm form-control" id="validationSCreated_at"
                            name="start_at" oninput="changeValue(this)" value="{{$start_at}}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationCreated_at">{{ __('itstock.buy-accessorie.up-to-date') }}</label>
                        <input type="date" class="form-control-sm form-control" id="validationECreated_at" name="end_at"
                            value="{{$end_at}}" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2 mb-2">
                        <button class="btn-shadow btn btn-info" type="submit" style="margin-top: 30px">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                            </span>
                            {{ __('itstock.buy-accessorie.search') }}</button>
                    </div>
                </div>
            </form>
            <script>
                if (document.getElementById("validationSCreated_at").value) {
                    document.getElementById("validationECreated_at").readOnly = false;
                }
            </script>
            <script src="{{asset('assets\js\transactions\buy.js')}}" defer></script>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{ __('itstock.buy-accessorie.purchase-list') }}</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('itstock.buy-accessorie.equipment') }}</th>
                            <th>{{ __('itstock.buy-accessorie.number') }}</th>
                            <th>{{ __('itstock.buy-accessorie.ir-no') }}</th>
                            <th>{{ __('itstock.buy-accessorie.po-no') }}</th>
                            <th>{{ __('itstock.buy-accessorie.buyer') }}</th>
                            <th>{{ __('itstock.buy-accessorie.a-date') }}</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($transactions)
                        @foreach ($transactions as $key => $item)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->accessorie->access_name}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->ir_no}}</td>
                            <td>{{$item->po_no}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->created_at}}</td>
                            <td><a href="{{route('it.equipment.buy.edit',$item->id)}}"
                                    class="btn btn-primary btn-sm float-left"> {{ __('itstock.buy-accessorie.detail') }}
                                </a>
                            </td>
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
@stop