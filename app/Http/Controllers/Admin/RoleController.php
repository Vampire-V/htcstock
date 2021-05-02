<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\PermissionsServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Models\Role;
use App\Services\IT\Interfaces\SystemServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'permission_name' => 'required|nullable',
        ]);
        DB::beginTransaction();
        try {
            $role = $this->rolesService->create(['name' => $request->name, 'slug' => $request->slug]);
            if ($role->exists) {
                $role->permissions()->sync($request->permission_name);
                $request->session()->flash('success', 'create roles success');
            } else {
                $request->session()->flash('error', 'create roles fail!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('admin.roles.index');
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
            $role = $this->rolesService->query()->with('permissions')->find($id);
            $system = $this->systemService->systemIn([substr($role->slug, strpos($role->slug, '-') + 1)])->first();
            if ($system) {
                $permissions = $this->permissionsService->systemIn([$system->id]);
            }else{
                $permissions = $this->permissionsService->dropdown();
            }
            
            return \view('admin.roles.edit',\compact('role','permissions'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
            'slug' => 'required',
            'permission_name' => 'required|nullable',
        ]);
        DB::beginTransaction();
        try {
            $role = $this->rolesService->find($id);
            if ($this->rolesService->update([$request->name], $id)) {
                $role->permissions()->sync($request->permission_name);
                $request->session()->flash('success', 'update roles success');
            } else {
                $request->session()->flash('error', 'update roles fail!');
            }
            // $this->rolesService->update($request->except(['_token','_method']),$id) ? $request->session()->flash('success','update roles success') : $request->session()->flash('error','update roles fail!');

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
