<?php

namespace App\Http\Controllers\Accessories;

use App\Accessories;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AccessoriesRepositoryInterface;
use App\Repositories\Interfaces\HistoriesRepositoryInterface;
use Illuminate\Http\Request;

class AccessoriesController extends Controller
{
    private $accessoriesRepository;
    private $historiesRepository;

    public function __construct(AccessoriesRepositoryInterface $accessoriesRepository,HistoriesRepositoryInterface $historiesRepositoryInterface)
    {
        $this->accessoriesRepository = $accessoriesRepository;
        $this->historiesRepository = $historiesRepositoryInterface;
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $accessories = $this->accessoriesRepository->all();
            return view('accessories.accessories', \compact('accessories'));
        } catch (\Throwable $th) {
            throw $th;
        }
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
            $request->validate([
                'name' => 'required|max:255',
                'unit' => 'required',
                'qty' => 'required|numeric|min:0|not_in:0',
            ]);
            // create to histories to be
            $accessories = $this->accessoriesRepository->store($request);
            if (!$accessories->exists) {
                $request->session()->flash('error', 'error accessories fail!');
            } 
            if (!$this->historiesRepository->historiesImport($accessories->id,$request->qty)->exists){
                $request->session()->flash('error', 'error histories fail!');
            }
            $request->session()->flash('success', $accessories->name . ' เพิ่มอุปกรณ์แล้ว !');
            return \redirect()->route('accessories.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Accessories  $accessories
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accessories = $this->accessoriesRepository->edit($id);
        $accessories->qty = $this->historiesRepository->historyStockQty($accessories->id);
        return $accessories;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Accessories  $accessories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'unit' => 'required',
                'qty' => 'required|numeric|min:0|not_in:0',
            ]);
            $accessories = $this->accessoriesRepository->edit($id);
            if (!$this->accessoriesRepository->update($accessories, $request)->exists) {
                $request->session()->flash('error', 'error accessories update fail!');
                return \redirect()->route('accessories.index');
            }
            if (!$this->historiesRepository->historiesImport($accessories->id,$request->qty)->exists) {
                $request->session()->flash('error', 'error histories create fail!');
                return \redirect()->route('accessories.index');
            }
            $request->session()->flash('success', $accessories->name . ' เพิ่มอุปกรณ์แล้ว !');
            return \redirect()->route('accessories.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Accessories  $accessories
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $accessories = $this->accessoriesRepository->delete($id);
            return \redirect()->route('accessories.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
