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
            <div>{{ __('itstock.buy-accessorie.purchase-edit') }}
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
                {{-- <a href="{{route('it.equipment.buy.create')}}" class="btn-shadow btn btn-info">
                <span class="btn-icon-wrapper pr-2 opacity-7">
                    <i class="fa fa-business-time fa-w-20"></i>
                </span>
                ซื้อ</a> --}}
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{ __('itstock.buy-accessorie.purchase-form') }}
        </div>
        <div class="card-body">
            {{-- <h5 class="card-title">{{ __('itstock.buy-accessorie.purchase-form') }}</h5> --}}
            <form class="needs-validation" novalidate action="{{route('it.equipment.buy.update',$transaction->id)}}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationAccess_id" class="">{{ __('itstock.buy-accessorie.equipment') }}</label>
                        <select name="access_id" id="validationAccess_id" class="form-control-sm form-control select2">
                            <option value="">--เลือก--</option>
                            @foreach ($accessories as $item)
                            <option value="{{$item->access_id}}"
                                {{$item->access_id == $transaction->access_id ? 'selected' : ''}}>{{$item->access_name}}
                            </option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationQty">{{ __('itstock.buy-accessorie.quantity') }}</label>
                        <input type="number" class="form-control-sm form-control" id="validationQty" name="qty"
                            value="{{$transaction->qty}}">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationUnitCost">{{ __('itstock.buy-accessorie.unit-cost') }}</label>
                        <input type="number" class="form-control-sm form-control" id="validationUnitCost"
                            name="unit_cost" value="{{$transaction->unit_cost}}" required>
                        <div class="invalid-feedback">
                            Please provide a valid UnitCost.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationPonumber">{{ __('itstock.buy-accessorie.po-no') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationPonumber" name="po_no"
                            value="{{$transaction->po_no}}" placeholder="12345678">
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationinvoice">{{ __('itstock.buy-accessorie.invoice') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationinvoice" name="invoice_no"
                            value="{{$transaction->invoice_no}}" placeholder="12345678">
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationVendor">{{ __('itstock.buy-accessorie.vendor') }}</label>
                        <select name="vendor_id" id="validationVendor" class="form-control-sm form-control" required>
                            <option value="">--เลือก--</option>
                            @foreach ($vendorDropdown as $item)
                            <option value="{{$item->code}}" {{$item->code == $transaction->vendor_id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid Vendor.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationIrnumber">{{ __('itstock.buy-accessorie.ir-no') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationIrnumber" name="ir_no"
                            value="{{$transaction->ir_no}}" placeholder="123456789">
                        <div class="invalid-feedback">
                            Please choose a IR No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationIrDate">{{ __('itstock.buy-accessorie.ir-date') }}</label>
                        <input type="date" class="form-control-sm form-control" id="validationIrDate" name="ir_date"
                            value="{{ isset($transaction->ir_date) ? $transaction->ir_date->format('Y-m-d') : ""}}">
                        <div class="invalid-feedback">
                            Please choose a IR Date.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="trans_desc">{{ __('itstock.buy-accessorie.remark') }}</label>
                        <textarea name="trans_desc" id="trans_desc" class="form-control-sm form-control"
                            rows="3">{{$transaction->trans_desc}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="invalidCheck" name="ref_no" value="ยกเลิก"
                            {{is_null($transaction->ref_no)?'':'checked'}}>
                        <label class="form-check-label" for="invalidCheck">
                            {{ __('itstock.buy-accessorie.cancel-purchase') }}
                        </label>
                        <div class="invalid-feedback">
                            You must agree before submitting.
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit"
                    style="margin-top: 5px">{{ __('itstock.buy-accessorie.submit-form') }}</button>
            </form>
            <script src="{{asset('assets\js\transactions\buy.js')}}"></script>
        </div>
    </div>
</div>
@stop