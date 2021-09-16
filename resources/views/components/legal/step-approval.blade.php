<div class="main-card mb-3 card">
    <div class="card-header">Step approval</div>
    <div class="card-body">
        {{-- <h5 class="card-title">step approval</h5> --}}
        <button class="accordion active">Approval Info</button>
        <div class="panel" style="max-height: 100%">
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Requestor</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($contract->approvalDetail)
                        @foreach ($contract->approvalDetail as $key => $item)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->user->name }} - {{$item->user->email}}</td>
                            <td>{{$item->status}}</td>
                            <td>{{$item->comment}}</td>
                            <td>{{$item->created_at}}</td>
                        </tr>
                        @endforeach
                        @endisset

                    </tbody>
                </table>
            </div>
        </div>
        <form id="approval-contract-form" action="{{route('legal.contract.approval',$contract->id)}}"
            method="POST">
            @csrf
            @if ($permission === 'Write')
            <div class="form-row">
                <div class="col-md-3 mb-3">
                    <label for="validationStatus"><strong>Status</strong></label>
                    <select name="status" id="status" class="form-control-sm form-control" style="cursor: pointer">
                        <option value="">Choouse...</option>
                        <option value="reject">Reject</option>
                        <option value="approval">Approval</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-12">
                    <label for="validationComment"><strong>Comment</strong></label>
                    <textarea class="form-control-sm form-control" name="comment" rows="5"></textarea>
                </div>
            </div>
            @endif
        </form>
        <hr>


        {{-- <a class="btn-shadow mr-3 btn btn-dark" type="button" href="{{url()->previous()}}">Back</a>
        <button class="mr-3 btn btn-success" type="submit" onclick="event.preventDefault();
    document.getElementById('approval-contract-form').submit();">Submit</button> --}}
    </div>
</div>