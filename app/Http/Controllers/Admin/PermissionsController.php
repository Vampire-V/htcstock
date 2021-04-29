<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IT\PermissionsFormRequest;
use App\Services\IT\Interfaces\PermissionsServiceInterface;

class PermissionsController extends Controller
{
    protected $permissionsService;
    public function __construct(PermissionsServiceInterface $permissionsServiceInterface) {
        $this->permissionsService = $permissionsServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $permissions = $this->permissionsService->all()->get();
            return \view('admin.permissions.index')->with('permissions',$permissions);
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
        return \view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionsFormRequest $request)
    {
        try {
            $this->permissionsService->create($request->except(['_token'])) ? $request->session()->flash('success','create permission success') : $request->session()->flash('error','create permission fail!');
            return \redirect()->route('admin.permissions.index');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
        return 'permission show';
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
            return \view('admin.permissions.edit')->with('permission',$this->permissionsService->find($id));
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
    public function update(PermissionsFormRequest $request, $id)
    {
        try {
            $this->permissionsService->update($request->except(['_token']),$id) ? $request->session()->flash('success','update permission success') : $request->session()->flash('error','update permission fail!');
            return \redirect()->route('admin.permissions.edit',$id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
