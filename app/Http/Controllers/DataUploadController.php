<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload-data');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->store('uploads');

  

        return back()->with('success', 'Data uploaded successfully!');
    }
}
