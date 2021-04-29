<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use App\Http\Requests\IT\AccessorieFormRequest;
use App\Models\IT\Accessories;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccessoriesController extends Controller
{
    protected $accessoriesService;
    protected $transactionsService;
    public function __construct(AccessoriesServiceInterface $accessoriesServiceInterface, TransactionsServiceInterface $transactionsServiceInterface)
    {
        $this->accessoriesService = $accessoriesServiceInterface;
        $this->transactionsService = $transactionsServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedAccessorys = collect($request->accessory);
        try {
            $accessories = $this->accessoriesService->filter($request);
            $accessorys = $this->accessoriesService->dropdown();
            return \view('it.accessorie.list', \compact('accessories', 'query', 'selectedAccessorys', 'accessorys'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $query = $request->all();
        $selectedAccessorys = collect($request->accessory);
        try {
            $accessories = $this->accessoriesService->filter($request);
            $accessorys = $this->accessoriesService->dropdown();
            return \view('it.all.index', \compact('accessories', 'query', 'selectedAccessorys', 'accessorys'));
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
        try {
            return \view('it.accessorie.create');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessorieFormRequest $request)
    {
        try {
            $isCreate = $this->accessoriesService->create($request->except(['_token']));
            if (!$isCreate) {
                $request->session()->flash('error', ' has been create fail');
                return \back();
            }
            $request->session()->flash('success', ' has been create success');
            return \redirect()->route('it.equipment.management.edit', $isCreate->access_id);
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
            $accessorie = $this->accessoriesService->find($id);
            return \view('it.accessorie.edit')->with('accessorie', $accessorie);
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
    public function update(AccessorieFormRequest $request, $id)
    {
        try {
            // \dd($request->equipment_image);
            $isUpdate = $this->accessoriesService->update($request->except(['_token', '_method']), $id);
            if (!$isUpdate) {
                $request->session()->flash('error', ' has been update fail');
                return \back();
            }
            $request->session()->flash('success', ' has been update success');
            return \redirect()->route('it.equipment.management.edit', $id);
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
        try {
            $accessorie = $this->accessoriesService->find($id);
            if (!$this->accessorieInTransaction($accessorie)) {
                Session::flash('error',  ' has been delete fail');
                return \back();
            }

            $delete = $this->accessoriesService->destroy($id);
            if (!$delete) {
                Session::flash('error',  ' has been delete fail');
                return \back();
            }
            Session::flash('success',  ' has been delete');
            return \back();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    private function accessorieInTransaction(Accessories $accessorie)
    {
        if ($accessorie->transaction()->get()->sum('qty') > 0) {
            return false;
        }
        return true;
    }
}
