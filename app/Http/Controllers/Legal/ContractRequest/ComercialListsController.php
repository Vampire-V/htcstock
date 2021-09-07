<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Models\Legal\LegalComercialList;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
        //Validate data
        $data = $request->only('id', 'desc', 'qty', 'unit_price', 'price', 'discount', 'amount', 'contract_dests_id');
        $validator = Validator::make($data, [
            'desc' => 'required|max:255',
            'qty' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0.01',
            // 'discount' => 'numeric|min:0.00',
            'contract_dests_id' => 'required'
        ],[
            'desc.required' => 'The description field is required.',
            'desc.max' => 'The description field is max length 255',
            'qty.required' => 'The quantity field is number.',
            'unit_price.required' => 'The unit price field is number.',
            // 'discount.numeric' => 'The discount field is number.',
            'contract_dests_id.required' => 'The description field is required.',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase add fail..',
                'data' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $model = new LegalComercialList();
            $model->description = $data['desc'];
            $model->qty = $data['qty'];
            $model->unit_price = $data['unit_price'];
            $model->price = $model->unit_price * $model->qty;
            $model->discount = $data['discount'] ?? 0.00;
            $model->amount = $model->price - $model->discount;
            $model->contract_dests_id = $data['contract_dests_id'];
            $model->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase add fail..',
                'data' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Purchase add success..',
            'data' => $model
        ], Response::HTTP_CREATED);
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
        return \response()->json(['result' => 'delete success!', 'status' => true], 200);
    }
}
