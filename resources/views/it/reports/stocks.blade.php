@extends('layouts.app')
@section('sidebar')
@section('style')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endsection
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
            <div>{{ __('itstock.stock.stock') }}
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
                {{-- <a href="#" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    ค้นหา</a> --}}
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{ __('Form search') }}
        </div>
        <div class="card-body">
            <form action="{{route('it.check.stocks_list')}}" method="GET">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationAccess_id" class="">{{ __('itstock.stock.equipment') }}</label>
                        <select name="access_id" id="validationAccess_id" class="form-control-sm form-control select2">
                            <option value="">--เลือก--</option>
                            @foreach ($accessories as $item)
                            <option value="{{$item->access_id}}" {{$access_id == $item->access_id ? 'selected' : ''}}>
                                {{$item->access_name}}</option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn-shadow btn btn-info" type="submit" style="margin-top: 30px">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-business-time fa-w-20"></i>
                            </span>
                            {{ __('itstock.stock.search') }}</button>
                    </div>
                </div>
            </form>
            <script src="{{asset('assets\js\transactions\stock.js')}}" defer></script>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{ __('itstock.stock.stock') }}
        </div>
        <div class="card-body">
            {{-- <h5 class="card-title">{{ __('itstock.stock.stock') }}</h5> --}}
        <a href="{{route('it.generateAccessoriesPDF')}}" target="_blank"
        rel="noopener noreferrer" class="card-subtitle mb-2 btn btn-warning float-right">{{ __('itstock.stock.print') }}</a>
            <div class="table-responsive">
                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('itstock.stock.equipment') }}</th>
                            <th>{{ __('itstock.stock.number') }}</th>
                            {{-- <th>วันที่</th>
                            <th>สร้างโดย</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $key => $item)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->accessorie?->access_name}}</td>
                            <td>{{$item->quantity}}</td>
                            {{-- <td>{{$item->created_at}}</td>
                            <td>{{$item->transactionCreated->name}}</td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->appends($query)->links() }}
        </div>
    </div>
</div>
@stop
