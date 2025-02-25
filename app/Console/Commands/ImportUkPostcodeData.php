<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImportUkPostcodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-uk-postcode-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download CSVs with UK Postcode Data and Import into database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading zip file of postcode data...');
        $this->download();
        $this->info('Zip file downloaded');

        $this->info('Unzipping zip file of postcode data...');
        $this->unzip();
        $this->info('Zip file unzipped');

        $this->info('Processing Postcode CSV file...');
        $this->importFullData();
        $this->info('Postcode CSV files processed');

        $this->info('Creating Postcode DB entries...');
        $this->insertPostcodes();
        $this->info('Postcode entry complete');
    }

    /**
     * Download the zip file of postcode CSVs
     */
    protected function download()
    {
        $url = 'https://parlvid.mysociety.org/os/ONSPD/2022-11.zip';
        Storage::put('location/postcode-data.zip', Http::get($url)->getBody());
    }

    /**
     * Unzip the zip file of postcode data
     */
    protected function unzip()
    {
        $zip = new ZipArchive();
        if ($zip->open(Storage::path('location/postcode-data.zip')) === true) {
            $zip->extractTo(Storage::path('location/'));
            $zip->close();
        }
    }

    /**
     *  Load full postcode data CSV into a temp database table
     */
    protected function importFullData()
    {
        $pdo = DB::connection()->getPdo();
        $pdo->exec("TRUNCATE tmp_postcode_data");
        $file = storage_path('app/private/location/Data/ONSPD_NOV_2022_UK.csv');

        $sql = <<<EOD
                LOAD DATA LOCAL INFILE '$file'
                INTO TABLE tmp_postcode_data
                FIELDS TERMINATED BY ','
                ENCLOSED BY '"'
                LINES TERMINATED BY '\r\n'
                IGNORE 1 ROWS;
        EOD;
        return $pdo->exec($sql);
    }

    /**
     * Creates Postcode Database entries based on the temp full data table.
     * Postcode is unique index so any existing postcodes will be updated.
     * Postcode in temp table has a space, so I'm stripping that so I can do the same on input/lookup.
     */
    protected function insertPostcodes()
    {
        $sql = <<< EOD
            INSERT INTO postcodes (`postcode`, `lat`, `long`, `created_at`, `updated_at`)
            (SELECT REPLACE(`pcd`, ' ', ''), `lat`, `long`, NOW(), NOW()
             FROM tmp_postcode_data
            )
            ON DUPLICATE KEY UPDATE
            updated_at = NOW()
        EOD;

        $pdo = DB::connection()->getPdo();
        return $pdo->exec($sql);
    }
}
