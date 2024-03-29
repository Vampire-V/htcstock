<?php

namespace App\Http\Controllers\Legal\AdminManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreApproval;
use App\Models\Department;
use App\Models\Legal\LegalApproval;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\Legal\Interfaces\ApprovalServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    protected $approvalService;
    protected $departmentService;
    protected $userService;
    public function __construct(
        ApprovalServiceInterface $approvalServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        UserServiceInterface $userServiceInterface
    ) {
        $this->approvalService = $approvalServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->userService = $userServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $departments = $this->departmentService->all()->orderBy('name', 'asc')->get();
            // \dd($departments);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.AdminManagement.Approval.index')->with(['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            //code...
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.AdminManagement.Approval.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApproval $request)
    {
        $attributes = $request->except(['_token']);
        DB::beginTransaction();
        try {
            $approval = $this->approvalService->create($attributes);
            // \dd($approval);
            if (!$approval) {
                $request->session()->flash('error', 'error create!');
            } else {
                $request->session()->flash('success',  ' has been create');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $department = $this->departmentService->find($id);
            $users = $this->userService->dropdown();
            $depts = Department::where('id','<>',$id)->get();
            $approvals = $this->approvalService->approvalByDepartment($department);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.AdminManagement.Approval.edit')->with(['approvals' => $approvals, 'department' => $department, 'users' => $users, 'depts' => $depts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $approval = $this->approvalService->find($id);
            $approvalOver = $this->approvalService->approvalLevelAllOver($approval->levels, $approval->department_id);
            foreach ($approvalOver as $key => $value) {
                $value->levels -= 1;
                $value->save();
            }
            $this->approvalService->destroy($id);
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * approval levelUp the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function levelUp($id)
    {
        DB::beginTransaction();
        try {
            $approvalup = $this->approvalService->find($id);
            $approvaldown = $this->approvalService->approvalLevelLess($approvalup->levels, $approvalup->department_id);
            if ($approvalup->levels === 1) {
                return \redirect()->back();
            }
            $approvalup->levels -= 1;
            $approvalup->save();
            $approvaldown->levels += 1;
            $approvaldown->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * approval levelUp the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function levelDown($id)
    {
        DB::beginTransaction();
        try {
            $approvaldown = $this->approvalService->find($id);
            $approvalup = $this->approvalService->approvalLevelOver($approvaldown->levels, $approvaldown->department_id);
            if ($approvalup->levels === 1) {
                return \redirect()->back();
            }
            $approvalup->levels -= 1;
            $approvalup->save();
            $approvaldown->levels += 1;
            $approvaldown->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * approval levelUp the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function copyto(Request $request, $dept)
    {
        DB::beginTransaction();
        try {
            $depts = Department::find($dept);
            $new_clone = [];
            foreach ($depts->legalApprove as $approve) {
                $clone = [];
                foreach ($request->dept as $key => $item) {
                    $temp = $approve->toArray();
                    unset($temp['id']);
                    $temp['department_id'] = \intval($item);
                    \array_push($clone,$temp);
                }
                \array_push($new_clone,$clone);
            }
            
            $rr = \array_merge(...$new_clone);
            LegalApproval::whereIn('department_id',[$request->dept])->delete();
            \collect($rr)->sortBy('department_id')->each(function($item) {
                $newModels = LegalApproval::firstOrNew($item,$item);
                $newModels->save();
            });
            // $newModels = LegalApproval::create();
            // dd($newModels);
            // $newModels->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back()->with('success', "Copy data success...");
    }
}
