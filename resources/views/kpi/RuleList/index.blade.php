@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .app-main,
    div #loading {
        background-position: 50% 2%;
    }

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

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Rule Search</div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-3 col-xl-3">
                                <label for="ruleName">Rule Name :</label>
                                <input type="text" class="form-control form-control-sm" id="ruleName"
                                    placeholder="Rule Name" name="ruleName" value="{{$searchRuleName}}">
                            </div>
                            <div class="col-md-3 col-xl-3">
                                <label for="ruleCategory">Rule Category :</label>
                                <select id="validationRuleCategory" class="form-control-sm form-control"
                                    name="category_id[]" multiple>
                                    @isset($category)
                                    @foreach ($category as $item)
                                    <option value="{{$item->id}}" @if ($selectedCategory->contains($item->id))
                                        selected @endif>{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-3 col-xl-3">
                                <label for="ruleType">Rule Type :</label>
                                <select id="validationRuleType" class="form-control-sm form-control" name="rule_type[]"
                                    multiple>
                                    @isset($rulesType)
                                    @foreach ($rulesType as $item)
                                    <option value="{{$item->id}}" @if ($selectedRuleType->contains($item->id))
                                        selected @endif>{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="mb-2 mr-2 btn btn-primary mt-4">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Rule List
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="mb-2 mr-2 btn-transition btn btn-outline-focus" data-toggle="modal"
                            data-target="#modal-import"><span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="pe-7s-file"></i>
                                Import
                            </span></button>
                        <a href="{{route('kpi.rules.export')}}"
                            class="mb-2 mr-2 btn-transition btn btn-outline-focus"><span
                                class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="pe-7s-file"></i>
                                Export
                            </span></a>
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <a href="{{route('kpi.rule-list.create')}}" class="btn-shadow btn btn-info mb-2 mr-2">
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
                                <th>Rule Name</th>
                                <th>Rule Category</th>
                                <th>Calculate Type</th>
                                <th>Rule Type</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($rules)
                            @foreach ($rules as $key => $item)
                            <tr>
                                <th scope="row">{{$key + 1}}</th>
                                <td>{{$item->name}}</td>
                                <td class="truncate">{{$item->category->name}}</td>
                                <td>{{$item->calculate_type}}</td>
                                <td>{{$item->ruletype->name}}</td>
                                <td>
                                    <a href="{{route('kpi.rule-list.edit',$item->id)}}"
                                        class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info btn-sm">Edit
                                    </a>
                                    @can('parent-admin-kpi')
                                    <a href="{{route('kpi.rule-list.destroy',$item->id)}}"
                                        class="mb-2 mr-2 border-0 btn-danger btn btn-outline-info btn-sm" onclick="remove({{$item->id}})">Remove
                                    </a>
                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('kpi.rule-list.destroy', $item->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                @isset($rules)
                {{ $rules->appends($query)->links() }}
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import-label"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-import-label">Import Rule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-import-rule">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="mb-3 widget-content ">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Template file: </div>
                                        <div class="widget-subheading"></div>
                                    </div>
                                    <div class="widget-numbers ">
                                        <a href="{{route('kpi.rules.download-template')}}" class="widget-heading"
                                            target="_blank" rel="noopener noreferrer"><i class="pe-7s-download">
                                            </i></a>
                                    </div>
                                    {{-- <div class="widget-content-right"></div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group dropzone"><label for="rules">Import data to system
                                    :</label>
                                <input type="file" name="file_template" id="file_template" onchange="onFile(this)" />
                                <input type="hidden" name="path_file">
                            </div>
                        </div>
                    </div>
                </form>
                <ul>
                    {{--  --}}
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitFile(this)">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\rule\index.js')}}" defer></script>
<script>
    var submitFile = (e) => {
        // e.offsetParent.offsetParent.offsetParent.getElementsByClassName("close")[0].click()
        let path = document.getElementsByName('path_file')[0].value
        let failure = []
        let message = ""
        if (path) {
            setVisible(true)
            postRuleImport({file:path})
            .then(res => {
                if (res.status === 201) {
                    console.log(res.data);
                    failure = res.data.data
                    message = res.data.message
                    toast(res.data.message,res.data.status)
                }
            })
            .catch(error => {
                console.log(error.response.data);
                toast(error.response.data.message,"error")
            })
            .finally(() => {
                // document.getElementById('modal-import').getElementsByClassName("close")[0].click()
                let ul = $('#modal-import .modal-body ul')
                failure.forEach(item => {
                    ul.append(`<li>row: ${item.row} , column: ${item.column} , <span style="color:red;">message: ${item.message}</span></li>`)
                });
                setVisible(false)
                toastClear()
            })
        }

    }

    var onFile = (e) => {
        const config = {
            onUploadProgress: (progressEvent) => {
                let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                console.log(percentCompleted,'% upload')
            }
        }

        let data = new FormData()
        data.append('file', e.files[0])
        postUploadFile(data,config)
        .then(res => {
            console.log(res);
            if (res.status === 200) {
                document.getElementsByName('path_file')[0].value = res.data.data
            }
        })
        .catch(error => {
            console.log(error.response.data);
            toast(error.response.data.message,error.response.data.status)
            error.response.data.errors.file.forEach(element => {
                toast(element,'error')
            })
        })
        .finally(() => {
            toastClear()
        })
    }

    $('#modal-import').on('hide.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var modal = $(this)
        // var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        // removeAllChildNodes(modal.find('.modal-body #rule-name')[0])
        modal.find('form')[0].reset();
        modal.find('input[type="hidden"]').val('');
        modal.find('li').remove();
    })

    var remove = id => {
        if (confirm("Remove rule !")) {
            document.getElementById(`delete-form-${id}`).submit()
        }
        event.preventDefault();
    }

</script>
@endsection
