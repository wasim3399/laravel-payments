<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCsvChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $chunk;

    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    public function handle()
    {
        foreach ($this->chunk as $row) {
            // Insert data into the database
            DB::table('card_infos')->insert([
                'card_bin' => $columns[0] ?? null,
                'brand' => $columns[1] ?? null,
                'issuer' => $columns[2] ?? null,
                'type' => $columns[3] ?? null,
                'level' => $columns[4] ?? null,
                'iso_country' => $columns[5] ?? null,
                'country_card_issue' => $columns[6] ?? null,
                'iso_a3' => $columns[7] ?? null,
                'iso_number' => $columns[8] ?? null,
                'www' => $columns[9] ?? null,
                'phone' => $columns[10] ?? null,
                'extra1' => $columns[11] ?? null,
                'extra2' => $columns[12] ?? null,
                'extra3' => $columns[13] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
                // ... other fields
            ]);
        }

        // Delete the temporary file (if necessary)
        // Storage::delete($filePath);
    }
}

