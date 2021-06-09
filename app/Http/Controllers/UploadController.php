<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048'
        ]);
        DB::beginTransaction();
        try {
            $fileModel = new TemporaryFile;

            if ($request->file()) {
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $folder = \uniqid() . '-' . \now()->timestamp;

                $file->storeAs('kpi/' . $folder, $filename);

                $fileModel->folder = $folder;
                $fileModel->filename = $filename;
                $fileModel->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        DB::commit();
        return $this->successResponse($folder, "upload file", 200);
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
