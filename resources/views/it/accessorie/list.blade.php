@extends('layouts.app')
@section('style')
<style>
    .select2-selection__rendered li {
        margin: 6px 0px 4px;
    }
</style>
@endsection
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
            <div>{{ __('itstock.manage-accessorie.manage-devices') }}
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
                <a href="{{route('it.equipment.management.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    {{ __('itstock.manage-accessorie.add') }}</a>
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
            <form action="#" method="GET">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
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
                    <div class="col-md-4 mb-3">
                        <button class="btn-shadow btn btn-info" type="submit">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                            </span>
                            {{ __('itstock.manage-accessorie.search') }}</button>
                    </div>
                </div>
            </form>
            <script>
                function destroy(id) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure? '+id,
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('destroy-form'+id).submit();
                        }
                    })
                }
            </script>
            <script src="{{asset('assets\js\transactions\accessorie.js')}}" defer></script>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{ __('itstock.manage-accessorie.equipment-list') }}
        </div>
        <div class="card-body">
            {{-- <h5 class="card-title">{{ __('itstock.manage-accessorie.equipment-list') }}</h5> --}}
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        @isset($accessories)
                        @foreach ($accessories as $item)
                        <div class="col-md-4">
                            <div class="main-card mb-3 card"><img width="100%" src="{{url('storage/'.$item->image)}}"
                                    alt="Card image cap" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{{$item->access_name}}</h5>
                                    <h6 class="card-subtitle">{{$item->unit}}</h6>Some quick example text to build on
                                    the card title
                                    and make up the bulk of the card's content.
                                    <a href="{{route('it.equipment.management.edit',$item->access_id)}}"><button
                                            type="button"
                                            class="btn btn-secondary btn-sm float-center mr-1">{{ __('itstock.manage-accessorie.detail') }}</button></a>

                                    <button type="button" class="btn btn-danger btn-sm float-center"
                                        onclick="destroy({{$item->access_id}})">{{ __('itstock.manage-accessorie.delete') }}</button>
                                    <form id="destroy-form{{$item->access_id}}"
                                        action="{{route('it.equipment.management.destroy',$item->access_id)}}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endisset
                    </div>
                </div>
            </div>
            @isset($accessories)
            {{ $accessories->appends($query)->links() }}
            @endisset
        </div>
    </div>
</div>
@stop