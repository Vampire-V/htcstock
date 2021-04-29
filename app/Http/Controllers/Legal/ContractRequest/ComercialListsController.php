<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use Illuminate\Http\Request;

class ComercialListsController extends Controller
{
    protected $comercialListsService;
    public function __construct(ComercialListsServiceInterface $comercialListsServiceInterface)
    {
        $this->comercialListsService = $comercialListsServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'description' => 'required|max:255',
            'unit_price' => 'required|numeric|min:0.01',
            'discount' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
            'contract_dests_id' => 'required'
        ]);
        $attributes = $request->all();
        try {
            $comercialLists = $this->comercialListsService->create($attributes);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \response()->json(['id' => $comercialLists->contract_dests_id], 200);
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
            $comercialLists = $this->comercialListsService->comercialByContractID($id);
            return \response()->json($comercialLists->toArray(), 200);
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
        try {
            $this->comercialListsService->destroy($id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \response()->json(['result' => 'delete success!','status' => true], 200);
    }
}
