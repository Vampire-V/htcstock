<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    public function store(Request $request)
    {
        if ($request->hasFile('rules')) {
            $file = $request->file('rules');
            $filename = $file->getClientOriginalName();
            $folder = \uniqid() . '-' . \now()->timestamp;
            $file->storeAs('kpi/' . $folder,$filename);
            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename
            ]);

            return $folder;
        }
        return '';
    }
}
