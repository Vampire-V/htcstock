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
            <div>Transfer Rules
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
{{-- end title --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Employee Search</div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-4 col-xl-4">
                                <label for="userFrom">From :</label>
                                <select id="user_actual" class="form-control-sm form-control" name="user_actual[]"
                                    multiple>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}" @if($selectedUser->contains($user->id)) selected
                                        @endif >{{$user->username}} {{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-3 col-xl-3">
                                <label for="userTo">To :</label>
                                <select id="user_to" class="form-control-sm form-control" name="user_to">
                                    <option value="">tets...</option>
                                </select>
                            </div> --}}
                            <div class="col-md-3">
                                <button class="btn btn-primary"
                                    style="padding: 1.4%; position: absolute; bottom:0;">Search</button>
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
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="btn-shadow btn btn-warning mb-2 mr-2" data-toggle="modal"
                            data-target="#modal_transfer_rules">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="pe-7s-plus"></i>
                            </span>
                            Transfer to
                        </button>
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
                                {{-- <th>#</th> --}}
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
                                {{-- <td>
                                    <a href="{{route('kpi.rule-list.edit',$item->id)}}"
                                        class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info btn-sm">Edit
                                    </a>
                                    <a href="{{route('kpi.rule-list.destroy',$item->id)}}"
                                        class="mb-2 mr-2 border-0 btn-danger btn btn-outline-info btn-sm"
                                        onclick="remove({{$item->id}})">Remove
                                    </a>
                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('kpi.rule-list.destroy', $item->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td> --}}
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                @if($rules)
                {{ $rules->appends(request()->except('page'))->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
{{-- Modal --}}
<div class="modal fade" id="modal_transfer_rules" tabindex="-1" role="dialog"
    aria-labelledby="modal_transfer_rules-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_transfer_rules-label">Transfer Rule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- {{route('kpi.transfer-rules.transfer',['user_form' => $selectedUser->toArray()])}} --}}
                <form class="needs-validation" novalidate
                    action="{{route('kpi.transfer-rules.transfer',['user_form' => $selectedUser->toArray()])}}"
                    method="POST" enctype="multipart/form-data" id="transfer-form">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-6 col-xl-6">
                            <label for="userTo">To :</label>
                            <select id="user_to" class="form-control-sm form-control" name="user_to">
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->username}} {{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('transfer-form').submit();">Transfer rule</button>
            </div>

        </div>
    </div>
</div>
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\transferRules\index.js')}}" defer></script>
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
