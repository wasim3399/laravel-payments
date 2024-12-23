<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProcessCsvChunk;
use App\Models\FileUploadStatus;

class FileUploadController extends Controller
{
    protected $batchId;

    // Show upload form
    public function showUploadForm()
    {
        return view('file_upload.index');
    }

    // Handle file upload
    public function handleUpload(Request $request)
    {
        $file = $request->file('csv_file');

        # raw file data in array
        $csv = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Read all lines of the file

        # create chunks of the csv data
        $chunks = array_chunk($csv, 1000);

        $batch = Bus::batch([])->dispatch();

        # create chunk files and store on server
        foreach ($chunks as $chunk)
        {
            $batch->add(new ProcessCsvData($chunk));
        }
        return $batch;
    }
}
