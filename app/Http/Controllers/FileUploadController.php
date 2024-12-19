<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvData;
use App\Models\CardInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
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
        $rawData = file($file);

        # each row as separate array
        # need to understand this point
        # $csv = array_map('str_getcsv', $rawData); # commenting this to resolve error array to string conversion to have actual data
        $csv = $rawData;

        # create chunks of the csv data
        $chunks = array_chunk($csv, 1000);
        # create chunk files and store on server


        foreach($chunks as $key => $chunk) {
            $name = "/temp{$key}.csv";
            $path = public_path('uploads');
            file_put_contents($path. $name, $chunk);

        }

        # store data with queues
        $response = $this->store();

        return $response;
    }

    public function store()
    {
        $path = public_path('uploads');
        $files = glob($path . '/*.csv'); // Get all CSV files in the uploads folder

        foreach ($files as $file) {
            $data = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Read all lines of the file

            # dispatch job
            ProcessCsvData::dispatch($data);
        }

        return response()->json(['status' => true, 'message' => 'CSV files processed and data inserted successfully!']);
    }

}
