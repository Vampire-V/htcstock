<!-- Modal Add-->
<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="{{route('legal.adminmanagement.approval.store')}}"
                    method="POST" enctype="multipart/form-data" id="form-approval">
                    @csrf
                    <input type="hidden" name="department_id" id="department_id" value="{{$department->id}}">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Email:</label>
                        <select name="user_id" id="validationUserId" class="form-control-sm form-control" required>
                            <option value="">--เลือก--</option>
                            @foreach ($users as $item)
                            <option value="{{$item->id}}">{{$item->email}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Level:</label>
                        <input type="number" class="form-control-sm form-control" name="levels"
                            value="{{isset($approvals) ? $approvals->count() + 1 : 1}}" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('form-approval').submit()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Copy-->
<div class="modal fade" id="copydata" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="{{route('legal.adminmanagement.approval.copyto',$department->id)}}"
                    method="POST" enctype="multipart/form-data" id="form-copy">
                    @csrf
                    {{-- <input type="hidden" name="department_id" id="department_id" value="{{$department->id}}"> --}}
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Email:</label>
                        <select name="dept[]" id="dept" class="form-control-sm form-control" multiple required>
                            @foreach ($depts as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >Select All
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('form-copy').submit()">Copy</button>
            </div>
        </div>
    </div>
</div>