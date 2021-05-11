@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-monitor icon-gradient bg-mean-fruit"> </i>
            </div>
            <div>Evaluation
                <div class="page-title-subheading">This is an example evaluation search created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Evaluation Search</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="division">Division :</label>
                            <select class="form-control form-control-sm" id="division" name="division[]"
                                multiple>
                                @isset($divisions)
                                @foreach ($divisions as $division)
                                <option value="{{$division->id}}" @if ($selectDivision->contains($division->id))
                                    selected
                                    @endif>{{$division->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="department">Department :</label>
                            <select class="form-control form-control-sm" id="department" name="department[]"
                                multiple>
                                @isset($departments)
                                @foreach ($departments as $dept)
                                <option value="{{$dept->id}}" @if ($selectDepartment->contains($dept->id))
                                    selected
                                    @endif>{{$dept->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="position">Position :</label>
                            <select class="form-control-sm form-control" id="position" name="position[]" multiple>
                                @isset($positions)
                                @foreach ($positions as $position)
                                <option value="{{$position->id}}" @if ($selectPosition->contains($position->id))
                                    selected
                                    @endif>{{$position->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="mb-2 mr-2 btn btn-primary mt-4">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Staff List</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Division</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($users)
                        @foreach ($users as $key => $user)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$user->name }}</td>
                            <td>{{$user->divisions->name}}</td>
                            <td>{{$user->department->name}}</td>
                            <td>{{$user->positions->name}}</td>
                            <td><a href="{{route('kpi.staff.edit',$user->id)}}"
                                    class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info">Detail
                                </a></td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
                {{ $users->appends($query)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\evaluationForm\index.js')}}" defer></script>
@endsection