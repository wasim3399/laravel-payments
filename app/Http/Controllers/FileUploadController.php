<?php

namespace App\Http\Controllers;

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
        echo 'done';
    }

    public function store()
    {
        $path = public_path('uploads');
        $files = glob($path . '/*.csv'); // Get all CSV files in the uploads folder

        foreach ($files as $file) {
            $rows = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Read all lines of the file
            $insertData = []; // Array to store rows for bulk insert

            foreach ($rows as $index => $row) {
                // Skip header row if present
                if ($index === 0 && str_contains($row, 'card_bin')) {
                    continue;
                }

                // Split the row into columns using the delimiter
                $data = explode(';', $row);

                // Ensure the row has the correct number of columns before processing
                if (count($data) < 14) {
                    continue; // Skip invalid rows
                }

                // Prepare the data for bulk insert
                $insertData[] = [
                    'card_bin' => $data[0],
                    'brand' => $data[1],
                    'issuer' => $data[2],
                    'type' => $data[3],
                    'level' => $data[4],
                    'country_card_issue' => $data[5],
                    'iso_country' => $data[6],
                    'iso_a3' => $data[7],
                    'iso_number' => $data[8],
                    'www' => $data[9],
                    'phone' => $data[10],
                    'extra1' => $data[11],
                    'extra2' => $data[12],
                    'extra3' => $data[13],
                ];
            }

            // Perform bulk insert into the database
            if (!empty($insertData)) {
                CardInfo::insert($insertData);
            }
        }

        return response()->json(['message' => 'CSV files processed and data inserted successfully!']);
    }

}
