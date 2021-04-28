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
            <div>{{ __('itstock.lendings-accessorie.borrowing') }}
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
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{ __('itstock.lendings-accessorie.loan-form') }}</h5>
            <form class="needs-validation" novalidate action="{{route('it.equipment.lendings.store')}}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationAccess_id" class="">{{ __('itstock.lendings-accessorie.equipment') }}</label>
                        <select name="access_id" id="validationAccess_id" class="form-control-sm form-control select2"
                            onchange="checkQtyAccess(this)" required>
                            <option value="">--เลือก--</option>
                            @foreach ($accessories as $item)
                            <option value="{{$item->access_id}}">{{$item->access_name}}</option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationQty">{{ __('itstock.lendings-accessorie.quantity') }}</label>
                        <input type="number" class="form-control-sm form-control" id="validationQty" name="qty" value="" min="1"
                            oninput="quantity(this)" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationTrans_by" class="">{{ __('itstock.lendings-accessorie.borrowed-by') }}</label>
                        <select name="trans_by" id="validationTrans_by" class="form-control-sm form-control select2" required>
                            <option value="">--เลือก--</option>
                            @foreach ($users as $item)
                            <option value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }}</option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="trans_desc">{{ __('itstock.lendings-accessorie.remark') }}</label>
                        <textarea name="trans_desc" id="trans_desc" class="form-control-sm form-control" rows="3"></textarea>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top: 5px" disabled>{{ __('itstock.lendings-accessorie.submit-form') }}</button>
            </form>
            <script src="{{asset('assets\js\transactions\lendings.js')}}"></script>
        </div>
    </div>
</div>
@stop