@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-balance-scale icon-gradient bg-happy-fisher" aria-hidden="true"></i>
            </div>
            <div>Template Libary
                <div class="page-title-subheading">
                </div>
                <div id="imagePreview"></div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Template
                    <div role="group" class="btn-group-sm btn-group">

                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        @if ($isAdmin)
                        <button class="btn-shadow btn btn-danger" data-toggle="modal"
                            data-target="#modal-template-libary"><span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>Import File </button>
                        @endif

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover" id="table-template-libary">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agreement</th>
                                <th>Download Version</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($agreements)
                            @foreach ($agreements as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$item->name}}</td>
                                <td>
                                    @if ($item->template_libarys->count()>0)
                                    <i class="pe-7s-angle-down" style="cursor: pointer; font-size: 2em;"
                                        aria-hidden="true" onclick="templatetoggle(this)"></i>
                                    @endif</td>
                                <td>
                                </td>
                            </tr>
                            @foreach ($item->template_libarys as $template)
                            <tr hidden>
                                <th scope="row"></th>
                                <td></td>
                                <td>Download <a
                                        href="{{route('legal.template-libary.show',$template->id)}}">{{$template->version}}</a>
                                    @if ($isAdmin)
                                    &nbsp;&nbsp;<i type="submit" class="pe-7s-trash"
                                        style="cursor: pointer; font-size: 1.4em; color: red;"
                                        onclick="template_remove(this)"></i>
                                    <form hidden action="{{route('legal.template-libary.delete',$template->id)}}"
                                        method="POST" id="form-template-remove">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                                <td>
                                    {{$template->created_at}}
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal-template-libary" tabindex="-1" role="dialog"
    aria-labelledby="ModalLabelTemplateLibary" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelTemplateLibary">Template libary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{route('legal.template-libary.store')}}"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">File template <span style="color: red;">*</span></label>
                            <input type="file" class="form-control form-control-sm" name="file_template"
                                accept="application/pdf" id="file_template" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please choose a File template.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="template">Agreement <span style="color: red;">*</span></label>
                            <select id="agreement" class="form-control-sm form-control" name="agreement_id" required>
                                @isset($agreements)
                                @foreach ($agreements as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Agreement.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation')
        // Loop over them and prevent submission
        validationForm(forms)

        // fetch_user_evalate()


    }, false);
})();

document.getElementById('file_template').addEventListener('change', (e) => {
    let original_size = e.target.files[0].size //type bytes
    if ((original_size/1024) > 25) alert('The file is too large.')
})

function templatetoggle(e) {
    let table,click_row ,end_row;

    click_row = e.parentElement.parentElement.rowIndex
    table = document.getElementById('table-template-libary')
    end_row = e.parentElement.parentElement.rowIndex + 1

    for (const iterator of table.tBodies[0].rows) {
        if ((iterator.rowIndex > click_row) && (iterator.cells[0].textContent === "") && iterator.rowIndex <= end_row) {
            if (iterator.hidden) {
                iterator.hidden = false
                end_row += 1
            }else{
                iterator.hidden = true
                end_row += 1
            }
        }
    }

    if (e.classList[0] === 'pe-7s-angle-up') {
        e.classList.remove("pe-7s-angle-up")
        e.classList.add("pe-7s-angle-down")
    }else{
        e.classList.remove("pe-7s-angle-down")
        e.classList.add("pe-7s-angle-up")
    }
}

var template_remove = e => {
    if (confirm('Are you sure?')) {
        // Post the form
        e.nextElementSibling.submit()
    }
}
</script>
@endsection