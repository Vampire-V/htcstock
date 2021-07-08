<?php

namespace App\Http\Controllers\Admin;

use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ALL\UserResource;
use App\Models\Department;
use App\Models\Division;
use App\Models\GroupDivision;
use App\Models\Role;
use App\Models\System;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\SystemServiceInterface;
use App\Services\KPI\Service\UserApproveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{
    protected $userService, $rolesService, $departmentService, $systemService, $divisionService, $positionService, $userApproveService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        RoleServiceInterface $rolesServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        SystemServiceInterface $systemServiceInterface,
        DivisionServiceInterface $divisionServiceInterface,
        PositionServiceInterface $positionServiceInterface,
        UserApproveService $userApproveService
    ) {
        $this->userService = $userServiceInterface;
        $this->rolesService = $rolesServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->systemService = $systemServiceInterface;
        $this->divisionService = $divisionServiceInterface;
        $this->positionService = $positionServiceInterface;
        $this->userApproveService = $userApproveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $search = $request->search;
        $selectedDivision = \collect($request->division);
        $selectedDept = \collect($request->department);
        $selectedPosition = \collect($request->position);
        $selectedRole = \collect($request->user_role);
        try {
            $users = $this->userService->filter($request);
            $divisions = $this->divisionService->dropdown();
            $departments = $this->departmentService->dropdown();
            $positions = $this->positionService->dropdown();
            $roles = $this->rolesService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('admin.users.index', \compact('users', 'divisions', 'departments', 'positions', 'roles', 'search', 'selectedDivision', 'selectedDept', 'selectedPosition', 'selectedRole', 'query'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (Gate::denies('superadmin-admin') && Gate::denies('admin-kpi')) {
                return \redirect()->back()->with('error', "No authorization...");
            }
            $user = User::with(['user_approves','department', 'divisions', 'positions'])->where('id',$id)->first();
            // $this->userService->find($id);
            // dd($user);
            $userRoles = $user->roles()->get();
            $userSystems = $user->systems()->get();

            $roles = $this->rolesService->dropdown($user->roles->pluck('slug')->toArray());
            $systems = $this->systemService->dropdown($user->systems()->pluck('slug')->toArray());
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('admin.users.edit', \compact('user', 'roles', 'systems', 'userRoles', 'userSystems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'roles' => 'required|nullable',
        ]);
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            if ($this->userService->update(['name' => $request->name], $id)) {
                $user->roles()->sync($request->roles);
                $request->session()->flash('success', $user->name . ' user has been update');
            } else {
                $request->session()->flash('error', 'error flash message!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // denies คือ !=
            // allows คือ ==
            // ตรวจสอบ Role Gate::denies(UserEnum::SUPERADMIN) จาก AuthServiceProvider ถ้าไม่ใช้ Admin 
            if (Gate::denies(UserEnum::SUPERADMIN)) {
                return \redirect()->route('admin.users.index');
            }
            if ($this->userService->delete($id)) {
                $request->session()->flash('success', ' has been delete');
            } else {
                $request->session()->flash('error', 'error flash message!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('admin.users.index');
    }

    public function updateusers(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Gate::denies('for-superadmin-admin')) {
                return back();
            }

            $response = Http::get(ENV('USERS_UPDATE'))->json();
            if (!\is_null($response)) {
                $list_users = [];
                foreach ($response as $value) {
                    $user = User::where('username', $value['username'])->first();
                    if (\is_null($user)) {
                        $user = new User;
                    }
                    $user->password = $user->password ?? Hash::make(\substr($value['email'], 0, 1) . $value['username']);
                    $user->username = $user->username ?? $value['username'];
                    $user->translateOrNew('th')->name = $user->translate('th')->name ?? $value['name'];
                    $user->name_th = $user->translate('th')->name ?? $value['name'];
                    $user->email = $value['email'];
                    // $user->email_verifed_at = $user->email_verifed_at ?? \now();
                    $user->head_id = $user->head_id ?? $value['leader'];
                    $user->save();
                    $list_users[] = $value['username'];
                }
                User::whereNotIn('username', [...$list_users])->update(['resigned' => 1]); //update user ที่ออกไปแล้ว
                $all_user = User::NotResigned()->get();
                $systems = System::whereNotIn('slug', ['legal', 'it'])->get();
                $roles = Role::whereNotIn('slug', [UserEnum::SUPERADMIN, UserEnum::ADMINIT, UserEnum::ADMINLEGAL, UserEnum::USERLEGAL, UserEnum::ADMINKPI])->get();

                foreach ($all_user as $staff) {
                    // $staff->roles()->detach($roles->filter(fn($q) => $q->slug === UserEnum::MANAGERKPI)->first());
                    foreach ($systems as $system) {
                        if (!$staff->systems->contains('slug', $system->slug)) {
                            $staff->systems()->attach($system);
                        }
                    }
                    foreach ($roles as $role) {
                        if (!$staff->roles->contains('slug', $role->slug)) {
                            if ($role->slug === UserEnum::MANAGERKPI && $staff->username === strval($staff->head_id)) {
                                $staff->roles()->attach($role);
                            }
                            if ($role->slug !== UserEnum::MANAGERKPI) {
                                $staff->roles()->attach($role);
                            }
                        }
                    }
                }
                $request->session()->flash('success', 'has been update user');
            } else {
                $request->session()->flash('error', 'ติดต่อ กับ ' . ENV('USERS_UPDATE') . "ไม่ได้");
            }
        } catch (\Exception $e) {
            // dd($user->department_id, $department);
            // throw $e;
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return back();
    }

    public function addrole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $roles = $this->rolesService->roleIn($request->roles);
            foreach ($roles as $value) {
                $user->roles()->attach($value);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(true, "add role to user", 200);
    }

    public function removerole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->roles()->detach($request->role);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(true, "remove role for user", 200);
    }

    public function addsystem(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $systems = $this->systemService->systemIn($request->system);
            foreach ($systems as $value) {
                $user->systems()->attach($value);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(true, "add system to user", 200);
    }

    public function removesystem(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->systems()->detach($request->system);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(true, "remove system for user", 200);
    }

    public function operations()
    {
        try {
            $users = User::whereHas('roles', fn ($query) => $query->whereIn('role_id', [6]))->get();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($users, 'get operation all', 200);
    }

    public function dropdown()
    {
        try {
            $users = $this->userService->dropdown();
            return $this->successResponse($users, 'get user dropdown', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store_approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $users = [];
            $user = User::with('user_approves')->where('id',$id)->first();
            $level_start = $user->user_approves->count() + 1;
            for ($i = 0; $i < count($request->users); $i++) {
                $item = $request->users[$i];
                $users[] = ['user_approve' => \intval($item), 'level' => $i + $level_start];
            }

            $user->user_approves()->createMany($users);
            DB::commit();
            $new = User::with('user_approves')->where('id',$id)->first();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($new, "add level approved", 200);
    }

    public function update_approve(Request $request, $id)
    {
        if (!$request->users) {
            return $this->errorResponse("no information...", 500);
        }
        DB::beginTransaction();
        try {
            $length = count($request->users);
            for ($i = 0; $i < $length; $i++) {
                $item = $request->users[$i];
                if (!$this->userApproveService->update(['level' => $item['level']], $item['id'])) {
                    DB::rollBack();
                    return $this->errorResponse("ไม่สามารถ แก้ไข user approve : " . $item['id'] . " นี้ได้", 500);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(true, "Updated success.", 200);
    }

    public function delete_approve(Request $request, $id)
    {
        if (!$request->has('user_approve')) {
            return $this->errorResponse("ไม่มี ข้อมูล ที่จะ ลบ", 500);
        }
        DB::beginTransaction();
        try {
            if (!$this->userApproveService->destroy($request->user_approve)) {
                return $this->errorResponse("ไม่มี ข้อมูล ที่จะ ลบ", 500);
            }
            $user = $this->userService->find($id);
            $user->user_approves->each(function ($item, $key) {
                $item->level = $key + 1;
                $item->save();
            });
            DB::commit();
            return $this->successResponse(true, "Deleted success.", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function copy_approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $to = $request->users;
            $user = $this->userService->find($id);
            $user->user_approves->each(function ($item) use ($to) {
                foreach ($to as $key => $value) {
                    $new = $item->replicate()->fill([
                        'user_id' => intval($value)
                    ]);
                    $new->save();
                }
            });
            DB::commit();
            return $this->successResponse(true, "Copy success.", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
