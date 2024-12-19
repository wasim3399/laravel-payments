<?php

namespace App\Jobs;

use App\Models\CardInfo;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCsvData implements ShouldQueue
{
    use Batchable, Queueable;
    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $insertData = [];
        foreach ($this->data as $row) {

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
            dump(CardInfo::count());
        }
    }
}
