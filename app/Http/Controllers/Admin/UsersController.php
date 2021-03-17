<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\SystemServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{
    protected $userService;
    protected $rolesService;
    protected $departmentService;
    protected $systemService;
    protected $divisionService;
    protected $positionService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        RoleServiceInterface $rolesServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        SystemServiceInterface $systemServiceInterface,
        DivisionServiceInterface $divisionServiceInterface,
        PositionServiceInterface $positionServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->rolesService = $rolesServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->systemService = $systemServiceInterface;
        $this->divisionService = $divisionServiceInterface;
        $this->positionService = $positionServiceInterface;
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
        } catch (\Throwable $th) {
            throw $th;
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
            if (Gate::denies('for-superadmin-admin')) {
                return \redirect()->route('admin.users.index');
            }
            $user = $this->userService->find($id);
            $userRoles = $user->roles()->get();
            $userSystems = $user->systems()->get();

            $roles = $this->rolesService->dropdown($user->roles->pluck('slug')->toArray());
            $systems = $this->systemService->dropdown($user->systems()->pluck('slug')->toArray());
        } catch (\Throwable $th) {
            throw $th;
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
            // ตรวจสอบ Role Gate::denies('super-admin') จาก AuthServiceProvider ถ้าไม่ใช้ Admin 
            if (Gate::denies('super-admin')) {
                return \redirect()->route('admin.users.index');
            }
            if ($this->userService->delete($id)) {
                $request->session()->flash('success', ' has been delete');
            } else {
                $request->session()->flash('error', 'error flash message!');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
            $role = Role::where('name', 'user')->first();
            \dd(ENV('USERS_UPDATE'));
            $response = Http::retry(2, 100)->get(ENV('USERS_UPDATE'))->json();
            \dd($response);
            if (!\is_null($response)) {
                foreach ($response as $value) {
                    $user = User::firstOrNew(['username' => $value['username']]);
                    $user->name = $value['name'];
                    $user->email = $value['email'];
                    $user->password = Hash::make(\substr($value['email'], 0, 1) . $value['username']);
                    $user->save();
                    if (!$user) {
                        $request->session()->flash('error', 'error update username' . $value['username']);
                        return back();
                    }
                    $user->roles()->attach($role);
                }
            }
            
            $request->session()->flash('success', 'has been update user');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \response($user->roles->toJson());
    }

    public function removerole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->roles()->detach($request->role);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \response($user->roles->toJson());
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \response($user->systems->toJson());
    }

    public function removesystem(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->systems()->detach($request->system);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return \response($user->systems->toJson());
    }
}
