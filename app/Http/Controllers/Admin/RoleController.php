<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\PermissionsServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Models\Role;
use App\Services\IT\Interfaces\SystemServiceInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $rolesService, $permissionsService, $systemService;
    public function __construct(
        RoleServiceInterface $rolesServiceInterface,
        PermissionsServiceInterface $permissionsServiceInterface,
        SystemServiceInterface $systemServiceInterface
    ) {
        $this->rolesService = $rolesServiceInterface;
        $this->permissionsService = $permissionsServiceInterface;
        $this->systemService = $systemServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $roles = $this->rolesService->filter($request);
            $dropdown = $this->rolesService->dropdown();
            $selectedRole = collect($request->role);
            $query = $request->all();
            return \view('admin.roles.index', \compact('roles', 'selectedRole', 'dropdown', 'query'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('admin.roles.create')->with(['permissions' => $this->permissionsService->all()->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->rolesService->create($request->except(['_token'])) ? $request->session()->flash('success', 'create permission success') : $request->session()->flash('error', 'create permission fail!');
            return \redirect()->route('admin.roles.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'role show';
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
            $role = $this->rolesService->find($id);
            $system = $this->systemService->systemIn([substr($role->slug, strpos($role->slug, '-') + 1)])->first();
            $permissions = $this->permissionsService->systemIn([$system->id]);
            return \view('admin.roles.edit')->with(['role' => $role, 'permissions' => $permissions]);
        } catch (\Throwable $th) {
            throw $th;
        }
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
        $request->validate([
            'name' => 'required',
            'permission_name' => 'required|nullable',
        ]);
        try {
            $role = $this->rolesService->find($id);
            if ($this->rolesService->update([$request->name], $id)) {
                $role->permissions()->sync($request->permission_name);
                $request->session()->flash('success', 'update roles success');
            } else {
                $request->session()->flash('error', 'update roles fail!');
            }
            // $this->rolesService->update($request->except(['_token','_method']),$id) ? $request->session()->flash('success','update roles success') : $request->session()->flash('error','update roles fail!');
            return \redirect()->route('admin.roles.edit', $id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
