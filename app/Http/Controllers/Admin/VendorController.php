<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class VendorController extends Controller
{
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatevendor(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Gate::denies('for-superadmin-admin')) {
                $request->session()->flash('error', 'has been update vendor no auth');
                return back();
            }
            // DB::connection('vendor')->enableQueryLog(); // Enable query log
            $result = DB::connection('vendor')->select("SELECT [VendorCode], [name], [Address] FROM [ChequeDirect].[dbo].[Vendor] WHERE [VendorCode] LIKE 'B%'");
            // \dump($result);
            // dd(DB::connection('vendor')->getQueryLog());
            // $response = Http::retry(2, 100)->get(ENV('VENDOR_UPDATE'))->json();
            foreach ($result as $data) {
                $vendor = Vendor::firstOrNew(['code' => $data->VendorCode ]);
                $vendor->name = $data->name;
                $vendor->address = $data->Address;
                $vendor->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        $request->session()->flash('success', 'has been update vendor');
        DB::commit();
        return back();
    }
}
