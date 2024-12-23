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
        try {
            $insertData = [];
            foreach ($this->data as $row) {
                $data = explode(';', $row);
                if (count($data) < 14) {
                    continue;
                }
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

            if (!empty($insertData)) {
                CardInfo::insert($insertData);
            }
        } catch (\Exception $e) {
            \Log::error('Job failed: ' . $e->getMessage());
        }
    }
}
