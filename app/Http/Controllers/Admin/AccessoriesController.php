<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IT\AccessorieFormRequest;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessorieFormRequest $request)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function uploadfileequipment(Request $request)
    {
        // max 20 MB.
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png|max:20480',
        ]);
        if ($validator->fails()) {
            return \response()->json($validator->messages(), 400);
        }
        $date =  new Carbon();
        $segments = explode('/', \substr(url()->previous(), strlen($request->root())));
        try {
            $path = Storage::disk('public')->put(
                $segments[1] . '/' . $segments[2] . '/accessories/' . $date->isoFormat('YYYYMD'),
                $request->file('file'),
            );
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \response()->json(['path' => $path]);
    }
}
